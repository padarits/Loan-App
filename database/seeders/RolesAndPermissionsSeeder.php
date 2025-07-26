<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
use App\Models\Role;
use App\Models\Permission;  
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Pārbaudi un izveido atļaujas, ja tās vēl neeksistē
        $permissions = [
            'edit transport',
            'delete transport',
            'create transport',
        ];
        $allPermissions = [];

        foreach ($permissions as $permission) {
            $allPermissions[$permission] = Permission::firstOrCreate(['uuid' => (string) Str::uuid(), 'name' => $permission]);
        }

        // Pārbaudi un izveido lomas, ja tās vēl neeksistē
        $role = Role::firstOrCreate(['name' => 'transport-edit']);
//var_dump($role);
//exit();
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        //$role->save();

        // Piešķir atļaujas lomām
        $role->givePermissionTo('edit transport');
        $role->givePermissionTo('delete transport');
        $role->givePermissionTo('create transport');
        $role->save();
        //$adminRole->syncPermissions(Permission::all());
    }
}

