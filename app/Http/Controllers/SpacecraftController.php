<?php

namespace App\Http\Controllers;

use App\Models\Spacecraft;
use App\Models\CrewMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;

class SpacecraftController extends Controller
{
    public function index()
    {
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'spacecraft.command_module' => 'required|string',
            'spacecraft.lunar_module' => 'required|string',
            'spacecraft.crew' => 'required|array',
            'spacecraft.crew.*.name' => 'required|string',
            'spacecraft.crew.*.role' => 'required|string',
        ]);

        $spacecraftData = $request->input('spacecraft');

        $spacecraft = Spacecraft::create([
            'command_module' => $spacecraftData['command_module'],
            'lunar_module' => $spacecraftData['lunar_module'],
        ]);


        foreach ($spacecraftData['crew'] as $crewMemberData) {
            $crewMember = CrewMember::firstOrCreate(['name' => $crewMemberData['name']]);
            $spacecraft->crew()->attach($crewMember->id, ['role' => $crewMemberData['role']]);
        }

        return response()->json(['data' => ['code' => 201, 'message' => 'Корабль добавлен']], 201);
     
    }


    public function show(Request $request, $id)
    {
        $spacecraft = Spacecraft::with('crew')->find($id);

        if (!$spacecraft) {
            return response()->json(['message' => 'Корабль не найден'], 404);
        }

        return response()->json(['spacecraft' => $spacecraft]);
    }


    public function update(Request $request, Spacecraft $spacecraft)
    {
        $request->validate([
            'spacecraft.command_module' => 'required|string',
            'spacecraft.lunar_module' => 'required|string',
            'spacecraft.crew' => 'required|array',
            'spacecraft.crew.*.name' => 'required|string',
            'spacecraft.crew.*.role' => 'required|string',
        ]);

        $spacecraftData = $request->input('spacecraft');
        $spacecraft->update([
            'command_module' => $spacecraftData['command_module'],
            'lunar_module' => $spacecraftData['lunar_module'],
        ]);

        $spacecraft->crew()->detach(); 

        foreach ($spacecraftData['crew'] as $crewMemberData) {
            $crewMember = CrewMember::firstOrCreate(['name' => $crewMemberData['name']]);
            $spacecraft->crew()->attach($crewMember->id, ['role' => $crewMemberData['role']]);
        }

        return response()->json(['data' => ['code' => 200, 'message' => 'Корабль обновлен']], 200);

    }

    public function destroy(Spacecraft $spacecraft)
    {
        $spacecraft->delete();
        return response(null, 204);
    }
}
