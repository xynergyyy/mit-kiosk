<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // One and only Administrator account
        User::updateOrCreate(
            ['email' => 'admin@mit-maps.com'],
            [
                'name' => 'MIT Maps Administrator',
                'password' => Hash::make('mit_v3_987251_admin'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'bryecastro@gmail.com'],
            [
                'name' => 'Brye Castro',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Faculty / Consultants / Counselor Accounts
        $faculty1 = User::updateOrCreate(
            ['email' => 'johndoe@mit-maps.com'],
            [
                'name' => 'Dr. John Doe',
                'title' => 'Dean of Computer Science',
                'password' => Hash::make('faculty_password_123'),
                'role' => 'faculty',
                'availability_status' => 'available',
            ]
        );

        $faculty2 = User::updateOrCreate(
            ['email' => 'janesmith@mit-maps.com'],
            [
                'name' => 'Prof. Jane Smith',
                'title' => 'IT Department Chair',
                'password' => Hash::make('faculty_password_123'),
                'role' => 'faculty',
                'availability_status' => 'busy',
            ]
        );

        $faculty3 = User::updateOrCreate(
            ['email' => 'dishrexd22@yahoo.com'],
            [
                'name' => 'Faculty Dishrexd',
                'title' => 'Dean of Engineering',
                'password' => Hash::make('Shark123'),
                'role' => 'faculty',
                'availability_status' => 'available',
            ]
        );

        $faculty4 = User::updateOrCreate(
            ['email' => 'katemalona@gmail.com'],
            [
                'name' => 'Kate Malona',
                'title' => 'Guidance Specialist',
                'password' => Hash::make('bayadnyodaw123'),
                'role' => 'faculty',
                'availability_status' => 'available',
            ]
        );

        $faculty5 = User::updateOrCreate(
            ['email' => 'alexanderalfonsoviii@gmail.com'],
            [
                'name' => 'Alexander Alfonso VIII',
                'title' => 'Professor of Mathematics',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'availability_status' => 'available',
            ]
        );

        $principal = User::updateOrCreate(
            ['email' => 'principal@mit-maps.com'],
            [
                'name' => 'Dr. Albus Dumbledore',
                'title' => 'School Principal',
                'password' => Hash::make('principal_password_123'),
                'role' => 'principal',
                'availability_status' => 'available',
            ]
        );

        $counselor = User::updateOrCreate(
            ['email' => 'counselor@mit-maps.com'],
            [
                'name' => 'Ms. Sarah Jenkins',
                'title' => 'Lead Guidance Counselor',
                'password' => Hash::make('counselor_password_123'),
                'role' => 'guidance_counselor',
                'availability_status' => 'available',
            ]
        );

        // Waypoints with assigned staff
        $lobby = \App\Models\Waypoint::updateOrCreate(
            ['location_code' => 'WP-0001'],
            [
                'name' => 'Main Lobby',
                'description' => 'The main entrance lobby of the MIT campus. A central meeting point for visitors.',
                'floor' => '1',
                'coordinates_x' => 50,
                'coordinates_y' => 50,
                // Free demo equirectangular panorama from Pannellum
                'pano_url' => 'https://pannellum.org/images/alma.jpg',
            ]
        );
        $lobby->assigned_user_id = $counselor->id;
        $lobby->save();
        $lobby->assignedUsers()->sync([$counselor->id]);

        $wing = \App\Models\Waypoint::updateOrCreate(
            ['location_code' => 'WP-0002'],
            [
                'name' => 'Faculty Wing',
                'description' => 'The administrative hub where faculty offices and meeting rooms are located.',
                'floor' => '2',
                'coordinates_x' => 30,
                'coordinates_y' => 35,
                // Another free demo panorama
                'pano_url' => 'https://pannellum.org/images/cerro-toco-0.jpg',
            ]
        );
        $wing->assigned_user_id = $faculty1->id;
        $wing->save();
        $wing->assignedUsers()->sync([$faculty1->id, $faculty2->id]);
    }
}
