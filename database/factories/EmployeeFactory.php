<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'personnel_number' => 'P'.fake()->unique()->numerify('#####'),
            'full_name' => fake()->name(),
            'department_id' => Department::query()->inRandomOrder()->value('id'),
            'gender' => fake()->randomElement([Gender::Male, Gender::Female]),
            'status' => EmployeeStatus::Active,
        ];
    }
}
