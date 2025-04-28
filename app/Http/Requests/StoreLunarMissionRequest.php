<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLunarMissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mission.name' => ['required', 'string', 'max:255'],
            'mission.launch_details.launch_date' => ['required', 'date'],
            'mission.launch_details.launch_site.name' => ['required', 'string', 'max:255'],
            'mission.launch_details.launch_site.location.latitude' => ['required', 'numeric'],
            'mission.launch_details.launch_site.location.longitude' => ['required', 'numeric'],
            'mission.landing_details.landing_date' => ['required', 'date'],
            'mission.landing_details.landing_site.name' => ['required', 'string', 'max:255'],
            'mission.landing_details.landing_site.coordinates.latitude' => ['required', 'numeric'],
            'mission.landing_details.landing_site.coordinates.longitude' => ['required', 'numeric'],
            'mission.spacecraft_id' => ['required', 'integer', 'exists:spacecrafts,id'],
        ];

    }
}
