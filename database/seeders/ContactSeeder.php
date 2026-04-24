<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 3) as $i) {
            Contact::updateOrCreate([
                'email' => 'contact' . $i . '@example.com',
            ], [
                'name' => 'Contact ' . $i,
                'email' => 'contact' . $i . '@example.com',
                'subject' => 'Subject ' . $i,
                'message' => 'This is a seeded contact message.',
                'status' => 'new',
            ]);
        }
    }
}
