<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);
        $authorRole = Role::create(['name' => 'author']);
        
        // Permisos Files
        Permission::create(['name' => 'files.*']);
        Permission::create(['name' => 'files.list']);
        Permission::create(['name' => 'files.create']);
        Permission::create(['name' => 'files.update']);
        Permission::create(['name' => 'files.read']);
        Permission::create(['name' => 'files.delete']);

        // Permisos Posts
        Permission::create(['name' => 'posts.*']);
        Permission::create(['name' => 'posts.list']);
        Permission::create(['name' => 'posts.create']);
        Permission::create(['name' => 'posts.update']);
        Permission::create(['name' => 'posts.read']);
        Permission::create(['name' => 'posts.delete']);

        //Permisos Places
        Permission::create(['name' => 'places.*']);
        Permission::create(['name' => 'places.list']);
        Permission::create(['name' => 'places.create']);
        Permission::create(['name' => 'places.update']);
        Permission::create(['name' => 'places.read']);
        Permission::create(['name' => 'places.delete']);

        //Permisos Role
        Permission::create(['name' => 'roles.*']);
        Permission::create(['name' => 'roles.list']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.update']);
        Permission::create(['name' => 'roles.read']);
        Permission::create(['name' => 'roles.delete']);

        //Permisos Permission
        Permission::create(['name' => 'permissions.*']);
        Permission::create(['name' => 'permissions.list']);
        Permission::create(['name' => 'permissions.create']);
        Permission::create(['name' => 'permissions.update']);
        Permission::create(['name' => 'permissions.read']);
        Permission::create(['name' => 'permissions.delete']);

        // Asignar Permisos
        $adminRole->givePermissionTo(['files.*', 'posts.*', 'places.*']);
        $editorRole->givePermissionTo(['files.list', 'files.update', 'files.read', 'posts.list', 'posts.update', 'posts.read', 'places.list', 'places.update', 'places.read', ]);
        $authorRole->givePermissionTo(['files.list', 'files.create', 'files.read', 'files.delete', 'posts.list', 'posts.create', 'posts.read', 'posts.delete', 'places.list', 'places.create', 'places.read', 'places.delete']);

        $name  = config('admin.name');
        $admin = User::where('name', $name)->first();
        $admin->assignRole('admin');
    }
}
