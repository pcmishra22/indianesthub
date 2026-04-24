<?php
namespace Database\Factories;

use App\Models\Dealer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DealerFactory extends Factory
{
    protected $model = Dealer::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'company_name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
        ];
    }
}
