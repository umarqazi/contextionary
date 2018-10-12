<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'username'  =>  'admin',
            'password'  =>  bcrypt('admin'),
            'name'      =>  'Administrator',
            'avatar'    =>  'public/images/Scrnsht from 2018-05-15 19-03-38.png',
            'created_at'=>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        //  admin_menu
        DB::table('admin_menu')->insert([
            'id'            =>  1,
            'parent_id'     =>  0,
            'order'         =>  1,
            'title'         =>  'Dashboard',
            'icon'          =>  'fa-bar-chart',
            'uri'           =>  '/',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  2,
            'parent_id'     =>  0,
            'order'         =>  2,
            'title'         =>  'Admin',
            'icon'          =>  'fa-tasks',
            'uri'           =>  '',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  3,
            'parent_id'     =>  2,
            'order'         =>  3,
            'title'         =>  'Admin Users',
            'icon'          =>  'fa-users',
            'uri'           =>  'auth/users',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  4,
            'parent_id'     =>  2,
            'order'         =>  4,
            'title'         =>  'Admin Roles',
            'icon'          =>  'fa-user',
            'uri'           =>  'auth/roles',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  5,
            'parent_id'     =>  2,
            'order'         =>  5,
            'title'         =>  'Admin Permissions',
            'icon'          =>  'fa-ban',
            'uri'           =>  'auth/permissions',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  6,
            'parent_id'     =>  8,
            'order'         =>  22,
            'title'         =>  'Menu',
            'icon'          =>  'fa-bars',
            'uri'           =>  'auth/menu',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  7,
            'parent_id'     =>  2,
            'order'         =>  6,
            'title'         =>  'Operation log',
            'icon'          =>  'fa-history',
            'uri'           =>  'auth/logs',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  8,
            'parent_id'     =>  0,
            'order'         =>  21,
            'title'         =>  'Settings',
            'icon'          =>  'fa-gears',
            'uri'           =>  NULL,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  9,
            'parent_id'     =>  0,
            'order'         =>  7,
            'title'         =>  'Users',
            'icon'          =>  'fa-tasks',
            'uri'           =>  NULL,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  10,
            'parent_id'     =>  9,
            'order'         =>  8,
            'title'         =>  'List of Users',
            'icon'          =>  'fa-users',
            'uri'           =>  'auth/simple-users',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  11,
            'parent_id'     =>  9,
            'order'         =>  9,
            'title'         =>  'User Roles',
            'icon'          =>  'fa-user',
            'uri'           =>  'auth/simple-users-roles',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  12,
            'parent_id'     =>  9,
            'order'         =>  10,
            'title'         =>  'User Permissions',
            'icon'          =>  'fa-ban',
            'uri'           =>  'auth/simple-users-permissions',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  16,
            'parent_id'     =>  0,
            'order'         =>  16,
            'title'         =>  'Languages',
            'icon'          =>  'fa-at',
            'uri'           =>  'auth/texts',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  17,
            'parent_id'     =>  0,
            'order'         =>  12,
            'title'         =>  'Fun Facts',
            'icon'          =>  'fa-futbol-o',
            'uri'           =>  'auth/fun-facts',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  18,
            'parent_id'     =>  0,
            'order'         =>  11,
            'title'         =>  'Tutorials',
            'icon'          =>  'fa-question-circle',
            'uri'           =>  'auth/tutorials/1',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  19,
            'parent_id'     =>  8,
            'order'         =>  18,
            'title'         =>  'Settings',
            'icon'          =>  'fa-gear',
            'uri'           =>  'auth/settings',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  20,
            'parent_id'     =>  17,
            'order'         =>  14,
            'title'         =>  'Pictionary',
            'icon'          =>  'fa-image',
            'uri'           =>  'auth/pictionary',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  21,
            'parent_id'     =>  17,
            'order'         =>  13,
            'title'         =>  'Fun Facts',
            'icon'          =>  'fa-soccer-ball-o',
            'uri'           =>  'auth/fun-facts',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  22,
            'parent_id'     =>  17,
            'order'         =>  15,
            'title'         =>  'Spot The Intruder',
            'icon'          =>  'fa-user-secret',
            'uri'           =>  'auth/spot-the-intruder',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  23,
            'parent_id'     =>  0,
            'order'         =>  17,
            'title'         =>  'Coin Deals',
            'icon'          =>  'fa-copyright',
            'uri'           =>  'auth/coins-deals',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  24,
            'parent_id'     =>  0,
            'order'         =>  19,
            'title'         =>  'Messages',
            'icon'          =>  'fa-comment-o',
            'uri'           =>  'auth/contact-us-msgs',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  25,
            'parent_id'     =>  0,
            'order'         =>  20,
            'title'         =>  'Feedbacks',
            'icon'          =>  'fa-commenting-o',
            'uri'           =>  '/auth/feedback-msgs',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_menu')->insert([
            'id'            =>  26,
            'parent_id'     =>  0,
            'order'         =>  18,
            'title'         =>  'Glossary',
            'icon'          =>  'fa-book',
            'uri'           =>  '/auth/glossary',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // admin_permissions
        DB::table('admin_permissions')->insert([
            'id'            =>  1,
            'name'          =>  'All permission',
            'slug'          =>  '*',
            'http_method'   =>  '',
            'http_path'     =>  '*',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_permissions')->insert([
            'id'            =>  2,
            'name'          =>  'Dashboard',
            'slug'          =>  'dashboard',
            'http_method'   =>  'GET',
            'http_path'     =>  '/',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_permissions')->insert([
            'id'            =>  3,
            'name'          =>  'Login',
            'slug'          =>  'auth.login',
            'http_method'   =>  '',
            'http_path'     =>  '/auth/login\r\n/auth/logout',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_permissions')->insert([
            'id'            =>  4,
            'name'          =>  'User setting',
            'slug'          =>  'auth.setting',
            'http_method'   =>  'GET,PUT',
            'http_path'     =>  '/auth/setting',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_permissions')->insert([
            'id'            =>  5,
            'name'          =>  'Auth management',
            'slug'          =>  'auth.management',
            'http_method'   =>  '',
            'http_path'     =>  '/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // admin_roles
        DB::table('admin_roles')->insert([
            'id'            =>  1,
            'name'          =>  'Administrator',
            'slug'          =>  'administrator',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_roles')->insert([
            'id'            =>  2,
            'name'          =>  'Sub Admin',
            'slug'          =>  'sub_admin',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // admin_role_menu
        DB::table('admin_role_menu')->insert([
            'role_id'       =>  1,
            'menu_id'       =>  2,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_menu')->insert([
            'role_id'       =>  1,
            'menu_id'       =>  8,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_menu')->insert([
            'role_id'       =>  1,
            'menu_id'       =>  9,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_menu')->insert([
            'role_id'       =>  1,
            'menu_id'       =>  10,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_menu')->insert([
            'role_id'       =>  1,
            'menu_id'       =>  11,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_menu')->insert([
            'role_id'       =>  1,
            'menu_id'       =>  12,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // admin_role_permissions
        DB::table('admin_role_permissions')->insert([
            'role_id'       =>  1,
            'permission_id' =>  1,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_permissions')->insert([
            'role_id'       =>  2,
            'permission_id' =>  1,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_permissions')->insert([
            'role_id'       =>  2,
            'permission_id' =>  2,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_permissions')->insert([
            'role_id'       =>  2,
            'permission_id' =>  3,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_permissions')->insert([
            'role_id'       =>  2,
            'permission_id' =>  4,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_role_permissions')->insert([
            'role_id'       =>  2,
            'permission_id' =>  5,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // admin_role_users
        DB::table('admin_role_users')->insert([
            'role_id'       =>  1,
            'user_id'       =>  1,
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
