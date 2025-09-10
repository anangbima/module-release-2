<?php

namespace Modules\ModuleRelease2\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ModuleRelease2\Models\Setting;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['string', 'integer', 'boolean', 'float', 'array', 'object']);

        return [
            'key' => $this->faker->unique()->word,
            'value' => match ($type) {
                'string' => $this->faker->sentence,
                'integer' => $this->faker->randomNumber(),
                'boolean' => $this->faker->boolean,
                'float' => $this->faker->randomFloat(2, 0, 100),
                'array' => $this->faker->words(3, true),
                'object' => json_encode(['key' => $this->faker->word, 'value' => $this->faker->sentence]),
            },
            'type' => $type,
        ];
    }
}
