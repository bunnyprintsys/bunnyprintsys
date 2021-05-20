<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $designer = Role::create(['name' => 'designer']);
        $production = Role::create(['name' => 'production']);

        $superadminUser = User::findOrFail(1);
        $superadminUser->assignRole('superadmin');

        $permissionArr = [
            'job-tickets-access',
            'job-tickets-exec-read',
            'job-tickets-create',
            'job-tickets-edit',
            'job-tickets-delete',
            'orders-access',
            'orders-read',
            'orders-create',
            'orders-edit',
            'orders-delete',
            'transactions-access',
            'transactions-read',
            'transactions-create',
            'transactions-edit',
            'transactions-delete',
            'user-management-access',
            'user-management-read',
            'user-management-create',
            'user-management-edit',
            'user-management-delete',
            'setting-profile-access',
            'setting-profile-read',
            'setting-profile-create',
            'setting-profile-edit',
            'setting-profile-delete',
            'setting-product-binding-access',
            'setting-product-binding-read',
            'setting-product-binding-create',
            'setting-product-binding-edit',
            'setting-product-binding-delete',
            'setting-pricing-access',
            'setting-pricing-read',
            'setting-pricing-create',
            'setting-pricing-edit',
            'setting-pricing-delete',
            'setting-transaction-access',
            'setting-transaction-read',
            'setting-transaction-create',
            'setting-transaction-edit',
            'setting-transaction-delete',
            'self-account-access',
            'self-account-read',
            'self-account-create',
            'self-account-edit',
            'self-account-delete',
        ];

        foreach($permissionArr as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin->givePermissionTo(Permission::all());
        $designer->givePermissionTo([
            'job-tickets-access',
            'job-tickets-exec-read',
            'job-tickets-create',
            'job-tickets-edit',
            'job-tickets-delete',
            'orders-access',
            'orders-read',
            'orders-create',
            'orders-edit',
            'orders-delete',
            'transactions-access',
            'transactions-read',
            'transactions-create',
            'transactions-edit',
            'transactions-delete',
            'self-account-access',
            'self-account-read',
            'self-account-create',
            'self-account-edit',
            'self-account-delete',
        ]);
        $production->givePermissionTo([
            'job-tickets-access',
            'job-tickets-edit',
            'job-tickets-delete',
            'self-account-access',
            'self-account-read',
            'self-account-create',
            'self-account-edit',
            'self-account-delete',
        ]);

    }
}
