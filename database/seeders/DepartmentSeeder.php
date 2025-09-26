<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Administracion', 'description' => 'Departamento de administración general y recursos humanos.', 'active' => true],
            ['name' => 'Sistemas', 'description' => 'Departamento de desarrollo y mantenimiento de sistemas informáticos.', 'active' => true],
            ['name' => 'Ventas', 'description' => 'Departamento encargado de las ventas y relaciones con clientes.', 'active' => true],
            ['name' => 'Contabilidad', 'description' => 'Departamento responsable de la gestión financiera y contable.', 'active' => true],
            ['name' => 'Tecnología y Soporte', 'description' => 'Departamento encargado de la tecnología y el soporte técnico.', 'active' => true],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
