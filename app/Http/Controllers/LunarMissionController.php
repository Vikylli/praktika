<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLunarMissionRequest;
use App\Models\LunarMission;
use Illuminate\Http\Request;

class LunarMissionController extends Controller
{
    public function store(Request $request)
{
    // Валидация остается той же
    $request->validate([
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

    // Теперь извлекаем данные из правильной структуры
    $missionData = $request->input('mission');  // Получаем весь массив 'mission'

    // Создаем новую миссию, используя правильные ключи
    $mission = LunarMission::create([
        'name' => $missionData['name'],  // Из $request['mission']['name']
        'spacecraft_id' => $missionData['spacecraft_id'],  // Из $request['mission']['spacecraft_id']
        'launch_date' => $missionData['launch_details']['launch_date'],
        'launch_site_name' => $missionData['launch_details']['launch_site']['name'],
        'launch_latitude' => $missionData['launch_details']['launch_site']['location']['latitude'],
        'launch_longitude' => $missionData['launch_details']['launch_site']['location']['longitude'],
        'landing_date' => $missionData['landing_details']['landing_date'],
        'landing_site_name' => $missionData['landing_details']['landing_site']['name'],
        'landing_latitude' => $missionData['landing_details']['landing_site']['coordinates']['latitude'],
        'landing_longitude' => $missionData['landing_details']['landing_site']['coordinates']['longitude'],
    ]);

    // Возвращаем ответ
    return response()->json([
        'data' => [
            'code' => 201,
            'message' => 'Миссия добавлена',
            'mission_id' => $mission->id
        ]
    ], 201);
}

    public function destroy($id)
    {
        LunarMission::destroy($id);
        return response()->json([],204);
    }
}