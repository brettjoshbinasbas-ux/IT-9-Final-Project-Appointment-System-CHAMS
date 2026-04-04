<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $staff = User::where('role','staff')->inRandomOrder()->first() ?? User::factory()->staff()->create();

        return [
            'client_id' => Client::inRandomOrder()->first()?->id ?? Client::factory()->create()->id,
            'staff_id' => $staff->id,
            'created_by' => User::where('role','admin')->inRandomOrder()->first()?->id ?? $staff->id,
            'service_type' => fake()->randomElement([
                'General Consultation',
                'Follow-up',
                'Physical Therapy',
                'Dental Cleaning',
                'Eye Checkup',
            ]),
            'appointment_date' => fake()->dateTimeBetween('now','+30 days')->format('Y-m-d'),
            'appointment_time' => fake()->time('H:i'),
            'status' => fake()->randomElement([
                'scheduled',
                'confirmed',
                'completed',
                'cancelled',
                'no_show',
            ]),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
