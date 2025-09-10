<?php

namespace Modules\ModuleRelease2\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ModuleRelease2\Models\LogActivity;

class LogActivityFactory extends Factory
{
    protected $model = LogActivity::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'action' => $this->faker->word,
            'model_type' => null,
            'model_id' => null,
            'description' => $this->faker->sentence,
            'data_before' => null,
            'data_after' => null,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'logged_at' => now(),
        ];
    }
}
