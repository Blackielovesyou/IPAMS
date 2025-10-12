<?php

namespace Database\Factories\admin;

use App\Models\admin\QrModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class QrModelFactory extends Factory
{
    protected $model = QrModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'userID' => User::factory(),
            'code' => Str::random(10), // or any other method to generate a unique code
        ];
    }
}
