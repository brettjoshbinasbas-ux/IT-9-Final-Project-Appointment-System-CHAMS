<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ServiceRecord;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //Defining specific value for uses later
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        //Defining specific admin for uses later
        User::factory()
            ->admin()
            ->create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
            ]);

        //Defining specific staff for uses later
        User::factory()
            ->staff()
            ->create([
                'name' => 'staff',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('staff123'),
            ]);

        //Defining specific receptionist for user later 
        User::factory()
            ->receptionist()
            ->create([
                'name' => 'Receptionist User',
                'email' => 'receptionist@gmail.com',
                'password' => Hash::make('receptionist123'),
            ]);

        //Create 3 random staffs
        User::factory()->staff()->count(3)->create();

        //Create 20 random Clients
        Client::factory()->count(20)->create();

        //Create 30 random Appointments
        Appointment::factory()->count(30)->create();

        //Create Appointment TODAY for badge activation testing
        $staff = User::where('role', 'staff')->first();
        $client = Client::inRandomOrder()->first();

        if ($staff && $client) {
            Appointment::create([
                'client_id' => $client->id,
                'staff_id' => $staff->id,
                'created_by' => $staff->id,
                'service_type' => 'Today\'s Checkup',
                'appointment_date' => now()->toDateString(),
                'appointment_time' => '10:00',
                'status' => 'scheduled',
                'notes' => 'Created by seeder for today',
            ]);
        }

        Appointment::where('status', 'completed')->each(function (Appointment $appointment) {
            ServiceRecord::create([
                'appointment_id' => $appointment->id,
                'client_id' => $appointment->client_id,
                'staff_id' => $appointment->staff_id,
                'description' => fake()->paragraph(),
                'service_date' => $appointment->appointment_date,
                'remarks' => fake()->optional()->sentence(),
            ]);
        });
    }
}
