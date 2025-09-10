<?php

namespace Modules\DesaModuleTemplate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\DesaModuleTemplate\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $connection = DB::connection(env('DB_CONNECTION', 'mysql'));

        $provinces = $connection->table('indonesia_provinces')->get();

        $provinceCode = null;
        $cityCode = null;
        $districtCode = null;
        $villageCode = null;

        if ($provinces->isNotEmpty()) {
            $province = $provinces->random();
            $provinceCode = $province->code;

            $cities = $connection->table('indonesia_cities')->where('province_code', $provinceCode)->get();
            if ($cities->isNotEmpty()) {
                $city = $cities->random();
                $cityCode = $city->code;

                $districts = $connection->table('indonesia_districts')->where('city_code', $cityCode)->get();
                if ($districts->isNotEmpty()) {
                    $district = $districts->random();
                    $districtCode = $district->code;

                    $villages = $connection->table('indonesia_villages')->where('district_code', $districtCode)->get();
                    if ($villages->isNotEmpty()) {
                        $village = $villages->random();
                        $villageCode = $village->code;
                    }
                }
            }
        }

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'province_code' => $provinceCode,
            'city_code' => $cityCode,
            'district_code' => $districtCode,
            'village_code' => $villageCode,
            'last_activity'     => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
