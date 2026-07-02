<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;

class BangaloreBuilderProjectsBatch1 extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'builder'=>'Prestige Group',
                'email'=>'prestige@indianesthub.local',
                'project'=>'Prestige Raintree Park',
                'address'=>'Whitefield, Bengaluru, Karnataka',
                'price_from'=>18000000,
                'price_to'=>42000000,
            ],
            [
                'builder'=>'Sobha Limited',
                'email'=>'sobha@indianesthub.local',
                'project'=>'Sobha Neopolis',
                'address'=>'Panathur Road, Bengaluru, Karnataka',
                'price_from'=>12000000,
                'price_to'=>35000000,
            ],
            [
                'builder'=>'Brigade Group',
                'email'=>'brigade@indianesthub.local',
                'project'=>'Brigade Sanctuary',
                'address'=>'Varthur Road, Bengaluru, Karnataka',
                'price_from'=>9000000,
                'price_to'=>25000000,
            ],
            [
                'builder'=>'Godrej Properties',
                'email'=>'godrej@indianesthub.local',
                'project'=>'Godrej Woodscapes',
                'address'=>'Budigere Cross, Bengaluru, Karnataka',
                'price_from'=>9000000,
                'price_to'=>22000000,
            ],
            [
                'builder'=>'Puravankara',
                'email'=>'puravankara@indianesthub.local',
                'project'=>'Purva Atmosphere',
                'address'=>'Thanisandra, Bengaluru, Karnataka',
                'price_from'=>14000000,
                'price_to'=>30000000,
            ],
        ];

        foreach($projects as $p){
            $builder = Builder::firstOrCreate(
                ['email'=>$p['email']],
                [
                    'name'=>$p['builder'],
                    'company_name'=>$p['builder'],
                    'password'=>Hash::make('password'),
                    'city'=>'Bengaluru',
                    'cities_operating'=>'Bengaluru',
                    'is_verified'=>true,
                    'status'=>'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id'=>$builder->id,'title'=>$p['project']],
                [
                    'builder_id'=>$builder->id,
                    'description'=>$p['project'].' is one of the prominent residential developments in Bengaluru.',
                    'project_type'=>'Residential',
                    'status'=>'Under Construction',
                    'address'=>$p['address'],
                    'city'=>'Bengaluru',
                    'state'=>'Karnataka',
                    'price_from'=>$p['price_from'],
                    'price_to'=>$p['price_to'],
                    'is_featured'=>true,
                ]
            );
        }

        $this->command->info('Seeded initial Bangalore builders.');
    }
}
