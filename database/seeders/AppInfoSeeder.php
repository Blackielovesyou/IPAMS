<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('app_info')->insert([
            'id' => 1,
            'logo' => NULL,
            'app_name' => 'App name',
            'banner' => NULL,
            'mission_vission' => NULL,
            'guidelines' => '<div style="position: relative; width: 100%; height: 0; padding-top: 56.2500%; padding-bottom: 0; box-shadow: 0 2px 8px 0 rgba(63,69,81,0.16); margin-top: 1.6em; margin-bottom: 0.9em; overflow: hidden; border-radius: 8px; will-change: transform;">
  <iframe loading="lazy" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0; margin: 0;" src="https://www.canva.com/design/DAGHiOUyxRg/fF8Yfi3c-N9SAF0QmclOIQ/view?embed" allowfullscreen="allowfullscreen" allow="fullscreen">
  </iframe>
</div>',
            'terms_and_condition' => NULL,
            'website' => NULL,
            'facebook' => NULL,
            'youtube' => NULL,
            'about_us' => NULL,
            'contact' => NULL,
            'email' => 'support@example.com',
            'address' => NULL,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
