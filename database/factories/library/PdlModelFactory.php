<?php

namespace Database\Factories\Library;

use App\Models\Library\PdlModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PdlModel>
 */
class PdlModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PdlModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'pdl_id' => $this->faker->unique()->numberBetween(1, 1000),
            'facility_id' => $this->faker->numberBetween(1, 100),
            'profile_img' => 'pdl/inmate.jpg',
            'name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'remark' => $this->faker->sentence(),
            'birthday' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
