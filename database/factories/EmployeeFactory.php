<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name, // Cambiar 'name' por 'full_name'
            'date' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail,
            'area' => $this->faker->randomElement(['Desarrollo', 'Marketing', 'Ventas', 'Administración']),
            'category' => $this->faker->randomElement(['Empleado', 'Directivo', 'Contratista']),
            'company_id' => \App\Models\Company::factory(), // Asegúrate de que esto genere un ID válido
            'satisfaction_level' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}