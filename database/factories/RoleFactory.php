<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'label' => $this->faker->words(2, true),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['name' => 'admin', 'label' => 'Administrator']);
    }

    public function staff(): static
    {
        return $this->state(fn () => ['name' => 'staff', 'label' => 'Staff Gudang']);
    }

    public function manager(): static
    {
        return $this->state(fn () => ['name' => 'manager', 'label' => 'Manager']);
    }
}
