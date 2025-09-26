<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_department","view_any_department","create_department","update_department","restore_department","restore_any_department","replicate_department","reorder_department","delete_department","delete_any_department","force_delete_department","force_delete_any_department","view_holiday","view_any_holiday","create_holiday","update_holiday","restore_holiday","restore_any_holiday","replicate_holiday","reorder_holiday","delete_holiday","delete_any_holiday","force_delete_holiday","force_delete_any_holiday","view_password::share","view_any_password::share","create_password::share","update_password::share","restore_password::share","restore_any_password::share","replicate_password::share","reorder_password::share","delete_password::share","delete_any_password::share","force_delete_password::share","force_delete_any_password::share","view_password::vault","view_any_password::vault","create_password::vault","update_password::vault","restore_password::vault","restore_any_password::vault","replicate_password::vault","reorder_password::vault","delete_password::vault","delete_any_password::vault","force_delete_password::vault","force_delete_any_password::vault","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_timesheet","view_any_timesheet","create_timesheet","update_timesheet","restore_timesheet","restore_any_timesheet","replicate_timesheet","reorder_timesheet","delete_timesheet","delete_any_timesheet","force_delete_timesheet","force_delete_any_timesheet","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","widget_StatsOverview","widget_PasswordVaultChart","widget_TimesheetChart"]},{"name":"Staff","guard_name":"web","permissions":["view_holiday","view_any_holiday","create_holiday","view_password::share","view_any_password::share","create_password::share","update_password::share","restore_password::share","restore_any_password::share","delete_password::share","delete_any_password::share","view_password::vault","view_any_password::vault","create_password::vault","update_password::vault","restore_password::vault","restore_any_password::vault","delete_password::vault","delete_any_password::vault","view_timesheet","view_any_timesheet","widget_StatsOverview","widget_PasswordVaultChart","widget_TimesheetChart"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
