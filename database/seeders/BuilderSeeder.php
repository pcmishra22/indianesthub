<?php

namespace Database\Seeders;

use App\Models\Builder;
use App\Models\BuilderProject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BuilderSeeder extends Seeder
{
    public function run(): void
    {
        $builder = Builder::firstOrCreate(
            ['email' => 'builder@demo.com'],
            [
                'name'             => 'Rajesh Kumar',
                'company_name'     => 'Demo Builders Pvt. Ltd.',
                'phone'            => '7340753780',
                'website'          => 'https://demobuilders.com',
                'city'             => 'Mumbai',
                'established_year' => '2008',
                'description'      => 'Demo Builders is a premier real estate developer with 15+ years of experience, delivering quality homes across Maharashtra.',
                'password'         => Hash::make('password'),
                'status'           => 'active',
            ]
        );

        // Sample Project 1
        BuilderProject::firstOrCreate(
            ['builder_id' => $builder->id, 'title' => 'Sunrise Heights Phase 2'],
            [
                'description'      => 'Premium residential township with world-class amenities.',
                'project_type'     => 'Residential',
                'status'           => 'Under Construction',
                'address'          => 'Andheri West, Mumbai',
                'city'             => 'Mumbai',
                'state'            => 'Maharashtra',
                'total_units'      => 250,
                'available_units'  => 180,
                'price_from'       => 7500000,
                'price_to'         => 15000000,
                'possession_date'  => '2027-03-31',
                'amenities'        => 'Swimming Pool, Gym, Club House, 24/7 Security, Power Backup, EV Charging, Children Play Area',
                'rera_id'          => 'P51700030123',
                'is_featured'      => true,
            ]
        );

        // Sample Project 2
        BuilderProject::firstOrCreate(
            ['builder_id' => $builder->id, 'title' => 'Green Valley Township'],
            [
                'description'      => 'Eco-friendly gated community with lush green surroundings.',
                'project_type'     => 'Township',
                'status'           => 'Upcoming',
                'address'          => 'Thane West',
                'city'             => 'Thane',
                'state'            => 'Maharashtra',
                'total_units'      => 400,
                'available_units'  => 400,
                'price_from'       => 5000000,
                'price_to'         => 9000000,
                'possession_date'  => '2028-12-31',
                'amenities'        => 'Park, Jogging Track, Amphitheatre, Smart Home Features',
                'rera_id'          => 'P51700030456',
                'is_featured'      => false,
            ]
        );

        $this->command->info('Builder seeder completed!');
        $this->command->info('Login: builder@demo.com / password');
    }
}
