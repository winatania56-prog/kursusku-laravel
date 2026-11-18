<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $participants = [
            [
                'name' => 'Alya Rahma',
                'email' => 'alya@example.test',
                'phone' => '081200000001',
            ],
            [
                'name' => 'Bima Putra',
                'email' => 'bima@example.test',
                'phone' => '081200000002',
            ],
            [
                'name' => 'Citra Nabila',
                'email' => 'citra@example.test',
                'phone' => null,
            ],
        ];

        foreach ($participants as $p) {
            Participant::firstOrCreate(
                ['email' => $p['email']],
                $p
            );
        }
    }
}
