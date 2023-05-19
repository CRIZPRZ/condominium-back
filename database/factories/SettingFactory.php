<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Setting;

class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'street' => $this->faker->streetName,
            'number' => $this->faker->word,
            'colony' => $this->faker->word,
            'city' => $this->faker->city,
            'state' => $this->faker->word,
            'country' => $this->faker->country,
            'cp' => $this->faker->numberBetween(-10000, 10000),
            'logo' => $this->faker->word,
        ];
    }
}
