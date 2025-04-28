<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLunarMissionRequest;
use App\Models\LunarMission;
use Illuminate\Http\Request;

class LunarMissionController extends Controller
{
    public function store(Request $request)
    {
        // Валидация входящих данных
        $validated = $request->validate([
            'mission.name' => 'required|string|max:255',
            
            'mission.launch_details.launch_date' => 'required|date',
            'mission.launch_details.launch_site.name' => 'required|string|max:255',
            'mission.launch_details.launch_site.location.latitude' => 'required|numeric|between:-90,90',
            'mission.launch_details.launch_site.location.longitude' => 'required|numeric|between:-180,180',
            
            'mission.landing_details.landing_date' => 'required|date|after_or_equal:mission.launch_details.launch_date',
            'mission.landing_details.landing_site.name' => 'required|string|max:255',
            'mission.landing_details.landing_site.coordinates.latitude' => 'required|numeric|between:-90,90',
            'mission.landing_details.landing_site.coordinates.longitude' => 'required|numeric|between:-180,180',
            
            'mission.spacecraft_id' => 'required|integer|exists:spacecrafts,id',
        ]);
        
        // Создаем новую миссию
        $mission = new LunarMission();
        $mission->name = $validated['mission']['name'];
        $mission->spacecraft_id = $validated['mission']['spacecraft_id'];
        
        // Сохраняем детали запуска и посадки как JSON
        $mission->launch_details = [
            'launch_date' => $validated['mission']['launch_details']['launch_date'],
            'launch_site' => [
                'name' => $validated['mission']['launch_details']['launch_site']['name'],
                'location' => [
                    'latitude' => $validated['mission']['launch_details']['launch_site']['location']['latitude'],
                    'longitude' => $validated['mission']['launch_details']['launch_site']['location']['longitude'],
                ],
            ],
        ];
        
        $mission->landing_details = [
            'landing_date' => $validated['mission']['landing_details']['landing_date'],
            'landing_site' => [
                'name' => $validated['mission']['landing_details']['landing_site']['name'],
                'coordinates' => [
                    'latitude' => $validated['mission']['landing_details']['landing_site']['coordinates']['latitude'],
                    'longitude' => $validated['mission']['landing_details']['landing_site']['coordinates']['longitude'],
                ],
            ],
        ];
        
        // Сохраняем миссию
        $mission->save();
        
        // Возвращаем ответ в соответствии с требуемым форматом
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => 'Миссия добавлена',
                'mission_id' => $mission->id
            ]
        ], 201);
    }
    // public function store(Request $request)
    // {
       
    //         $validated = $request->validate([
    //             'mission.name' => 'required|string|max:255',
            
    //             'mission.launch_details.launch_date' => 'required|date',
    //             'mission.launch_details.launch_site.name' => 'required|string|max:255',
    //             'mission.launch_details.launch_site.location.latitude' => 'required',
    //             'mission.launch_details.launch_site.location.longitude' => 'required',
                
    //             'mission.landing_details.landing_date' => 'required|date',
    //             'mission.landing_details.landing_site.name' => 'required|string|max:255',
    //             'mission.landing_details.landing_site.coordinates.latitude' => 'required',
    //             'mission.landing_details.landing_site.coordinates.longitude' => 'required',
                
    //             'mission.spacecraft_id' => 'required|integer|exists:spacecrafts,id',
    //         ]); 
            
            
    //         $mission = LunarMission::create([
    //             'name' => $validated['mission']['name'],
    //             'launch_date' => $request['mission']['launch_details']['launch_date'],
    //             'launch_site_name'=> $request['mission']['launch_details']['launch_site']['name'],
    //             'launch_latitude'=>$request['mission']['launch_details']['launch_date'],
    //             'launch_longitude'=>'',
    //             'landing_date'=>'',
    //             'landing_site_name'=>'',
    //             'landing_latitude'=>'',
    //             'landing_longitude'=>'',
    //             'spacecraft_id'=>'',

    //         ]);
    //         return response()->json(['message' => 'Миссия добавлена', 'mission_id' => $mission->id], 201);
    // }
    public function destroy($id)
    {
        LunarMission::destroy($id);
        return response()->json([],204);
    }
}