<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alya = Participant::where('email', 'alya@example.test')->first();
        $bima = Participant::where('email', 'bima@example.test')->first();

        $lrv = Course::where('code', 'LRV-101')->first();
        $php = Course::where('code', 'PHP-101')->first();

        if ($alya && $lrv) {
            Enrollment::firstOrCreate([
                'participant_id' => $alya->id,
                'course_id' => $lrv->id,
            ], [
                'status' => 'registered',
            ]);
        }

        if ($alya && $php) {
            Enrollment::firstOrCreate([
                'participant_id' => $alya->id,
                'course_id' => $php->id,
            ], [
                'status' => 'registered',
            ]);
        }

        if ($bima && $lrv) {
            Enrollment::firstOrCreate([
                'participant_id' => $bima->id,
                'course_id' => $lrv->id,
            ], [
                'status' => 'registered',
            ]);
        }
    }
}
