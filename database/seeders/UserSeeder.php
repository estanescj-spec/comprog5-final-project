<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name'               => 'Admin',
            'email'              => 'admin@gmail.com',
            'password'           => Hash::make('Password1.'),
            'role'               => 'admin',
            'email_verified_at'  => now(), // Admin auto-verified
        ]);

        // Customers
        User::create([
            'name'     => 'Robert Downey Jr.',
            'email'    => 'robert@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09111111111',
            'address'  => 'Unit 1203, Forbes Park Tower, Barangay Forbes Park, Makati City, Metro Manila',
            'profile_photo_path' => 'profile-photos/robert.jpg',
        ]);
        User::create([
            'name'     => 'Chris Evans',
            'email'    => 'chris@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09222222222',
            'address'  => 'Unit 8B, Project 6 Residences, Barangay Project 6, Quezon City, Metro Manila',
            'profile_photo_path' => 'profile-photos/chris.jpg',
        ]);
        User::create([
            'name'     => 'Scarlett Johansson',
            'email'    => 'scarlett@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09333333333',
            'address'  => 'Lot 7, Bonifacio High Street, Barangay Fort Bonifacio, Taguig City, Metro Manila',
            'profile_photo_path' => 'profile-photos/scarlett.jpg',
        ]);
        User::create([
            'name'     => 'Chris Hemsworth',
            'email'    => 'chris2@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09444444444',
            'address'  => 'Unit 15A, Ayala Alabang Village, Barangay Ayala Alabang, Muntinlupa City, Metro Manila',
            'profile_photo_path' => 'profile-photos/chris2.jpg',
        ]);
        User::create([
            'name'     => 'Mark Ruffalo',
            'email'    => 'mark@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09555555555',
            'address'  => 'Unit 702, Ortigas Center Residences, Barangay San Antonio, Pasig City, Metro Manila',
            'profile_photo_path' => 'profile-photos/mark.jpg',
        ]);
        User::create([
            'name'     => 'Jeremy Renner',
            'email'    => 'jeremy@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09666666666',
            'address'  => 'Unit 9C, Bayview Tower, Barangay Malate, Manila, Metro Manila',
            'profile_photo_path' => 'profile-photos/jeremy.jpg',
        ]);
        User::create([
            'name'     => 'Elizabeth Olsen',
            'email'    => 'elizabeth@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09777777777',
            'address'  => 'Unit 502, 5th Ave Residences, Barangay Greenhills, San Juan City, Metro Manila',
            'profile_photo_path' => 'profile-photos/elizabeth.jpg',
        ]);
        User::create([
            'name'     => 'Brie Larson',
            'email'    => 'brie@gmail.com',
            'password' => Hash::make('Password1.'),
            'role'     => 'customer',
            'phone'    => '09888888888',
            'address'  => 'Unit 12B, The Grove, Barangay West Triangle, Quezon City, Metro Manila',
            'profile_photo_path' => 'profile-photos/brie.jpg',
        ]);
    }
}
