<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;

class OrdersRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Izveidojam lomu 'orders'
        $ordersRole = Role::firstOrCreate([ 'name' => 'orders']);

        // Definējam nepieciešamās atļaujas
        $permissions = [
            'manage_projects',
            'manage_settings',
            'create_orders',
            'approve_orders',
            'manage_sellers',
            'manage_purchases',
        ];

        // Pārliecināmies, ka visas atļaujas pastāv un pievienojam tās lomai
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate([ 'name' => $permission]);
            $ordersRole->givePermissionTo($perm);
        }

        $this->command->info('Orders role with permissions seeded successfully.');
    }
}
