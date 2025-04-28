<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLunarMissionRequest;
use App\Models\LunarMission;
use Illuminate\Http\Request;

class LunarMissionController extends Controller
{
    public function store(Request $request)
{
    
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

    
    $missionData = $request->input('mission'); 

    
    $mission = LunarMission::create([
        'name' => $missionData['name'],  
        'spacecraft_id' => $missionData['spacecraft_id'],  
        'launch_date' => $missionData['launch_details']['launch_date'],
        'launch_site_name' => $missionData['launch_details']['launch_site']['name'],
        'launch_latitude' => $missionData['launch_details']['launch_site']['location']['latitude'],
        'launch_longitude' => $missionData['launch_details']['launch_site']['location']['longitude'],
        'landing_date' => $missionData['landing_details']['landing_date'],
        'landing_site_name' => $missionData['landing_details']['landing_site']['name'],
        'landing_latitude' => $missionData['landing_details']['landing_site']['coordinates']['latitude'],
        'landing_longitude' => $missionData['landing_details']['landing_site']['coordinates']['longitude'],
    ]);

  
    return response()->json([
        'data' => [
            'code' => 201,
            'message' => 'Миссия добавлена',
            'mission_id' => $mission->id
        ]
    ], 201);
}
public function update(Request $request, $id)
{
 
    $mission = LunarMission::find($id);

    if (!$mission) {
        return response()->json([
            'error' => 'Миссия не найдена'
        ], 404);
    }

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

    
    $missionData = $request->input('mission');


    $mission->update([
        'name' => $missionData['name'],
        'spacecraft_id' => $missionData['spacecraft_id'],
        'launch_date' => $missionData['launch_details']['launch_date'],
        'launch_site_name' => $missionData['launch_details']['launch_site']['name'],
        'launch_latitude' => $missionData['launch_details']['launch_site']['location']['latitude'],
        'launch_longitude' => $missionData['launch_details']['launch_site']['location']['longitude'],
        'landing_date' => $missionData['landing_details']['landing_date'],
        'landing_site_name' => $missionData['landing_details']['landing_site']['name'],
        'landing_latitude' => $missionData['landing_details']['landing_site']['coordinates']['latitude'],
        'landing_longitude' =>$missionData['landing_details']['landing_site']['coordinates']['longitude'],
    ]);


    return response()->json([
        'data' => [
            'code' => 200,
            'message' => 'Миссия обновлена'
        ]
    ], 200);
}



public function show($id)
{
    
    $mission = LunarMission::with(['spacecraft.crew'])->find($id);

    if (!$mission) {
        return response()->json([
            'error' => 'Миссия не найдена'
        ], 404);
    }

  
    return response()->json([
        'data' => [
            'mission' => [
                'name' => $mission->name,
                'launch_details' => [
                    'launch_date' => $mission->launch_date,
                    'launch_site' => [
                        'name' => $mission->launch_site_name,
                        'location' => [
                            'latitude' => $mission->launch_latitude,
                            'longitude' => $mission->launch_longitude,
                        ],
                    ],
                ],
                'landing_details' => [
                    'landing_date' => $mission->landing_date,
                    'landing_site' => [
                        'name' => $mission->landing_site_name,
                        'coordinates' => [
                            'latitude' => $mission->landing_latitude,
                            'longitude' => $mission->landing_longitude,
                        ],
                    ],
                ],
            ],
            'spacecraft' => [
                'name' => $mission->spacecraft->name,
                'crew' => $mission->spacecraft->crew->map(function ($member) {
                    return [
                        'name' => $member->name,
                        'role' => $member->role,
                    ];
                }),
            ],
        ]
    ], 200);
}

    public function destroy($id)
    {
        LunarMission::destroy($id);
        return response()->json([],204);
    }
}