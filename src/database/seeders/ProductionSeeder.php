<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedCountries();
    }

    private function seedCountries()
    {
        if (DB::table('countries')->count() > 0) {
            $this->command->info('Countries already seeded. Skipping...');
            return;
        }

        $path = database_path('sql/countries.sql');
        $sql = file_get_contents($path);

        DB::statement($sql);
    }
}
