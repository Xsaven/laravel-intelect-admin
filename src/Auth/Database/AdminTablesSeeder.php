<?php

namespace Lia\Auth\Database;

use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => 'Administrator',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
        ]);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            /*[
                'id' => 1,
                'parent_id' => 0,
                'order' => 0,
                'title' => 'Task Manager',
                'icon' => 'fa-slack',
                'uri' => 'task_manager',
                'type' => 'webix',
                'hotkey' => 'Alt+T',
            ],*/
            [
                'id' => 2,
                'parent_id' => 0,
                'order' => 2,
                'title' => 'Admin',
                'icon' => 'fa-tasks',
                'uri' => NULL,
                'type' => 'parent',
                'hotkey' => NULL,
            ],
            [
                'id' => 3,
                'parent_id' => 2,
                'order' => 0,
                'title' => 'Admin users',
                'icon' => 'fa-users',
                'uri' => 'administrators',
                'type' => 'webix',
                'hotkey' => 'Alt+U',
            ],
            [
                'id' => 4,
                'parent_id' => 2,
                'order' => 1,
                'title' => 'Roles',
                'icon' => 'fa-user',
                'uri' => 'roles',
                'type' => 'webix',
                'hotkey' => NULL,
            ],
            [
                'id' => 5,
                'parent_id' => 2,
                'order' => 2,
                'title' => 'Permission',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
                'type' => 'link',
                'hotkey' => NULL,
            ],
            [
                'id' => 6,
                'parent_id' => 2,
                'order' => 4,
                'title' => 'Menu',
                'icon' => 'fa-bars',
                'uri' => 'menu',
                'type' => 'webix',
                'hotkey' => 'Alt+M',
            ],
            [
                'id' => 7,
                'parent_id' => 2,
                'order' => 3,
                'title' => 'Operation log',
                'icon' => 'fa-history',
                'uri' => 'operation_log',
                'type' => 'webix',
                'hotkey' => 'Alt+L',
            ],
            [
                'id' => 8,
                'parent_id' => 19,
                'order' => 2,
                'title' => 'Helpers',
                'icon' => 'fa-heartbeat',
                'uri' => NULL,
                'type' => 'parent',
                'hotkey' => NULL,
            ],
            [
                'id' => 9,
                'parent_id' => 8,
                'order' => 0,
                'title' => 'Scaffold',
                'icon' => 'fa-keyboard-o',
                'uri' => 'helpers/scaffold',
                'type' => 'link',
                'hotkey' => NULL,
            ],
            [
                'id' => 12,
                'parent_id' => 20,
                'order' => 0,
                'title' => 'Routes List',
                'icon' => 'fa-list-alt',
                'uri' => 'rout_list',
                'type' => 'webix',
                'hotkey' => 'Alt+R',
            ],
            [
                'id' => 13,
                'parent_id' => 19,
                'order' => 3,
                'title' => 'Log Viewer',
                'icon' => 'fa-file-text',
                'uri' => 'log_viewer',
                'type' => 'webix',
                'hotkey' => NULL,
            ],
            [
                'id' => 14,
                'parent_id' => 0,
                'order' => 4,
                'title' => 'Config',
                'icon' => 'fa-toggle-on',
                'uri' => 'config',
                'type' => 'link',
                'hotkey' => NULL,
            ],
            [
                'id' => 15,
                'parent_id' => 8,
                'order' => 3,
                'title' => 'Api tester',
                'icon' => 'fa-sliders',
                'uri' => 'api-tester',
                'type' => 'link',
                'hotkey' => NULL,
            ],
            [
                'id' => 16,
                'parent_id' => 0,
                'order' => 1,
                'title' => 'File manager',
                'icon' => 'fa-folder-open',
                'uri' => 'filemanager',
                'type' => 'webix',
                'hotkey' => 'Alt+E',
            ],
            [
                'id' => 17,
                'parent_id' => 19,
                'order' => 4,
                'title' => 'Task scheduling',
                'icon' => 'fa-clock-o',
                'uri' => 'scheduling',
                'type' => 'webix',
                'hotkey' => NULL,
            ],
            [
                'id' => 18,
                'parent_id' => 19,
                'order' => 5,
                'title' => 'Exception Reporter',
                'icon' => 'fa-bug',
                'uri' => 'exception_reporter',
                'type' => 'webix',
                'hotkey' => 'Ctrl+Alt+E',
            ],
            [
                'id' => 19,
                'parent_id' => 0,
                'order' => 3,
                'title' => 'System',
                'icon' => 'fa-cogs',
                'uri' => NULL,
                'type' => 'parent',
                'hotkey' => NULL,
            ],
            [
                'id' => 20,
                'parent_id' => 19,
                'order' => 1,
                'title' => 'Routes',
                'icon' => 'fa-anchor',
                'uri' => NULL,
                'type' => 'parent',
                'hotkey' => NULL,
            ],
            [
                'id' => 21,
                'parent_id' => 20,
                'order' => 1,
                'title' => 'Manager',
                'icon' => 'fa-briefcase',
                'uri' => 'route-manager',
                'type' => 'link',
                'hotkey' => NULL,
            ],
            [
                'id' => 22,
                'parent_id' => 23,
                'order' => 0,
                'title' => 'Layouts',
                'icon' => 'fa-file-image-o',
                'uri' => '/layouts',
                'type' => 'link',
                'hotkey' => NULL,
            ],
            [
                'id' => 23,
                'parent_id' => 19,
                'order' => 0,
                'title' => 'Design',
                'icon' => 'fa-paint-brush',
                'uri' => NULL,
                'type' => 'parent',
                'hotkey' => NULL,
            ],
            [
                'id' => 24,
                'parent_id' => 20,
                'order' => 2,
                'title' => 'Manager v.2',
                'icon' => 'fa-tree',
                'uri' => 'router',
                'type' => 'link',
                'hotkey' => NULL,
            ],
            [
                'id' => 32,
                'parent_id' => 0,
                'order' => 5,
                'title' => 'Terminal',
                'icon' => 'fa-terminal',
                'uri' => 'terminal',
                'type' => 'modal',
                'hotkey' => 'Alt+Q',
            ]
        ]);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
