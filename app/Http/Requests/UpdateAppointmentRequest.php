<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var AuthManager $auth */

        $auth = auth();
        return $auth->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'staff_id' => 'required|exists:users,id',
            'service_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => ['required',Rule::in(array_keys(Appointment::STATUSES))],
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'The selected client does not exist.',
            'staff_id.required' => 'Please assign a staff member.',
            'staff_id.exists' => 'The selected staff member does not exist.',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past.',
        ];
    }
}
