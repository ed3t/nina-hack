<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'street' => $this->faker->streetAddress(),
        ];
    }
}
