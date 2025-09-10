<?php

namespace Modules\DesaModuleTemplate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\DesaModuleTemplate\Models\Notification;
use Modules\DesaModuleTemplate\Models\User;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'id' => Str::ulid()->toBase32(),
            'type' => 'App\\Notifications\\GenericNotification',
            'notifiable_id' => null,
            'notifiable_type' => User::class,
            'data' => [
                'title' => $this->faker->sentence(3),
                'message' => $this->faker->realText(rand(50, 200)), // panjang bervariasi
                'action_url' => $this->faker->url(),
                'type' => $this->faker->randomElement([
                    'success',
                    'warning',
                    'info',
                    'error',
                    'announcement',
                    '',
                ]),
            ],
            'read_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
