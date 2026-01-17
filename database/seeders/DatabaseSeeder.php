<?php

namespace Database\Seeders;

use App\Models\Barcode;
use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\Shift;
use App\Models\User;
use Database\Factories\DivisionFactory;
use Database\Factories\EducationFactory;
use Database\Factories\JobTitleFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan Seeder Admin (Akun Login)
        (new AdminSeeder)->run();

        // 2. Isi Data Divisi
        foreach (DivisionFactory::$divisions as $value) {
            if (Division::where('name', $value)->exists()) {
                continue;
            }
            Division::create(['name' => $value]);
        }

        // 3. Isi Data Pendidikan
        foreach (EducationFactory::$educations as $value) {
            if (Education::where('name', $value)->exists()) {
                continue;
            }
            Education::create(['name' => $value]);
        }

        // 4. Isi Data Jabatan
        foreach (JobTitleFactory::$jobTitles as $value) {
            if (JobTitle::where('name', $value)->exists()) {
                continue;
            }
            JobTitle::create(['name' => $value]);
        }

        // 5. Buat Barcode (Jika belum ada)
        if (Barcode::count() == 0) {
            Barcode::factory(1)->create(['name' => 'Barcode 1']);
        }
        // 6. Buat Shift
        if (!Shift::where('id', 1)->exists()) {
            Shift::create([
                'id' => 1,              // MEMAKSA ID 1
                'name' => 'Shift Regular',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
            ]);
        }
    }
}