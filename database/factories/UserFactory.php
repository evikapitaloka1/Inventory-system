<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => Role::factory()->staff(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role_id' => Role::factory()->admin()]);
    }

    public function staff(): static
    {
        return $this->state(fn () => ['role_id' => Role::factory()->staff()]);
    }

    public function manager(): static
    {
        return $this->state(fn () => ['role_id' => Role::factory()->manager()]);
    }
}
