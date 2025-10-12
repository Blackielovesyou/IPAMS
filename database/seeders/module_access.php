<?php

namespace Database\Seeders;

use App\Models\ModuleAccessDetails;
use App\Models\ModuleAccessModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class module_access extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        ModuleAccessDetails::truncate();

        $moduleAccessOptions = [
            ['code' => 1, 'name' => 'Dashboard', 'description' => 'Display the data in the system.'],
            ['code' => 1000, 'name' => 'User Management', 'description' => 'Manage user accounts and permissions.'],
            ['code' => 1001, 'name' => 'Audit Trail', 'description' => 'View and manage system audit logs.'],
            ['code' => 1002, 'name' => 'Settings', 'description' => 'Manage application settings and configurations.'],
        ];
        foreach ($moduleAccessOptions as $option) {
            ModuleAccessDetails::create([
                'code' => $option['code'],
                'name' => $option['name'],
                'description' => $option['description'],
            ]);
        }
    }
    public function down(): void
    {
        // You can optionally delete all records in ModuleAccessModel here
        // Example: ModuleAccessModel::truncate();
    }
}
