<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{

    /**
     * List of permissions to add.
     */
    private $permissions = [
        'user-list',
        'user-create',
        'user-edit',
        'user-delete',
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
        'product-list',
        'product-create',
        'product-edit',
        'product-delete'
    ];


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed permissions
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create admin role
        $adminRole = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $adminRole->syncPermissions($permissions);

        // Create user role
        $userRole = Role::create(['name' => 'User-roles']);
        $userPermissions = Permission::whereIn('name', ['role-list', 'role-create', 'role-edit', 'role-delete'])->get();
        $userRole->syncPermissions($userPermissions);

         // Create user role
         $userProduct = Role::create(['name' => 'User-product']);
         $userPermissions = Permission::whereIn('name', ['product-list', 'product-create', 'product-edit', 'product-delete'])->get();
         $userProduct->syncPermissions($userPermissions);


        // Create admin user and assign the admin role
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);
        $adminUser->assignRole($adminRole);

        // Create regular user and assign the user role with specific permissions
        $regularUser = User::create([
            'name' => 'Regular User Rol',
            'email' => 'userRol@example.com',
            'password' => Hash::make('password')
        ]);
        $regularUser->assignRole($userRole);

        $regularUser = User::create([
            'name' => 'Regular User Product',
            'email' => 'userProduct@example.com',
            'password' => Hash::make('password')
        ]);
        $regularUser->assignRole($userProduct);
    }
}
