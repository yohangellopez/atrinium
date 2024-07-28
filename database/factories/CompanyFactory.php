<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'document_type' => $this->faker->randomElement(['dni','cif','nie','nif','passport','other']),
            'document_number' => $this->faker->unique()->numerify('###########'),
            'contact_email' => $this->faker->unique()->safeEmail,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'user_id' => \App\Models\User::factory()->create()->id,
        ];
        
    }
}
