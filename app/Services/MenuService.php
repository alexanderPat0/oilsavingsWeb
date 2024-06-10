<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;


class MenuService
{
    public function getMenuForUser($name , $option)
    {
        if ($option == 2) {
            Log::info("Menu de superadmin");
            return $this->getSuperAdminMenu($name);
        } else if ($option == 1) {
            Log::info("Menu de admin");
            return $this->getAdminMenu($name);
        } else {
            Log::info("Menu de usuario normal");
            return $this->getUserMenu();
        }
    }

    protected function getSuperAdminMenu($name)
    {
        return [

            // Navbar items:

            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            [
                'text' => ($name ?? 'Your Profile'),
                'url' => 'profile',
                'topnav_right' => true,
                'icon' => 'fas fa-fw fa-user',
            ],

            // Sidebar items:
            // [
            //     'type' => 'sidebar-menu-search',
            //     'text' => 'search',
            // ],
            [
                'text' => 'blog',
                'url' => 'admin/blog',
                'can' => 'manage-blog',
            ],
            [
                'text' => ' Home',
                'url' => '',
                'icon' => 'fa fa-home',

            ],
            ['header' => 'Admins'],
            [
                'text' => ' Admin list',
                'url' => 'admins',
                'icon' => 'fa fa-user',

            ],
            ['header' => 'Users'],
            [
                'text' => ' User list',
                'url' => 'users',
                'icon' => 'fa fa-users',

            ],
            ['header' => 'Reviews'],
            [
                'text' => ' Reviews',
                'url' => 'reviews',
                'icon' => 'fa fa-comments',

            ],
            // 'label' => 4,
            // 'label_color' => 'success',
            // " style="color: #f2f2f2;"></i>

            ['header' => 'labels'],
            [
                'text' => 'important',
                'icon_color' => 'red',
                'url' => '#',
            ],
            [
                'text' => 'warning',
                'icon_color' => 'yellow',
                'url' => '#',
            ],
            [
                'text' => 'information',
                'icon_color' => 'cyan',
                'url' => '#',
            ],



        ];
    }

    protected function getAdminMenu($name)
    {
        return [

            // Navbar items:

            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            [
                'text' => ($name ?? 'Your Profile'),
                'url' => 'profile',
                'topnav_right' => true,
                'icon' => 'fas fa-fw fa-user',
            ],

            // Sidebar items:

            [
                'text' => 'blog',
                'url' => 'admin/blog',
                'can' => 'manage-blog',
            ],
            [
                'text' => ' Home',
                'url' => '',
                'icon' => 'fa fa-home',

            ],
            // [
            //     'text' => ' Admin list',
            //     'url' => 'admins',
            //     'icon' => 'fa fa-user',

            // ],
            ['header' => 'Users'],
            [
                'text' => ' User list',
                'url' => 'users',
                'icon' => 'fa fa-users',

            ],
            ['header' => 'Reviews'],
            [
                'text' => ' Reviews',
                'url' => 'reviews',
                'icon' => 'fa fa-comments',

            ],
            // 'label' => 4,
            // 'label_color' => 'success',
            // " style="color: #f2f2f2;"></i>
            [
                'text' => 'important',
                'icon_color' => 'red',
                'url' => '#',
            ],
            [
                'text' => 'warning',
                'icon_color' => 'yellow',
                'url' => '#',
            ],
            [
                'text' => 'information',
                'icon_color' => 'cyan',
                'url' => '#',
            ],



        ];
    }
    protected function getUserMenu()
    {
        return [

            // Navbar items:

            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            [
                'text' => 'Welcome!',
                // 'url' => 'profile',
                'topnav_right' => true,
                'icon' => 'fas fa-fw fa-user',
            ],
            // Sidebar items:
            [
                'text' => 'blog',
                'url' => 'admin/blog',
                'can' => 'manage-blog',
            ],
            // [
            //     'text' => ' Home',
            //     'url' => '',
            //     'icon' => 'fa fa-home',

            // ],
            // [
            //     'text' => ' Admin list',
            //     'url' => 'admins',
            //     'icon' => 'fa fa-user',

            // ],
            // ['header' => 'Users'],
            // [
            //     'text' => ' User list',
            //     'url' => 'users',
            //     'icon' => 'fa fa-users',

            // ],
            ['header' => 'Reviews'],
            [
                'text' => ' Reviews',
                'url' => 'reviews',
                'icon' => 'fa fa-comments',

            ],
            // 'label' => 4,
            // 'label_color' => 'success',
            // " style="color: #f2f2f2;"></i>
            // [
            //     'text' => 'important',
            //     'icon_color' => 'red',
            //     'url' => '#',
            // ],
            // [
            //     'text' => 'warning',
            //     'icon_color' => 'yellow',
            //     'url' => '#',
            // ],
            // [
            //     'text' => 'information',
            //     'icon_color' => 'cyan',
            //     'url' => '#',
            // ],

        ];
    }

    public function getMenuForEveryone(){
        return [
            
            // Navbar items:

            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            [
                'text' => 'Your Profile',
                'url' => 'profile',
                'topnav_right' => true,
                'icon' => 'fas fa-fw fa-user',
            ],

            // Sidebar items:
            // [
            //     'type' => 'sidebar-menu-search',
            //     'text' => 'search',
            // ],
            [
                'text' => 'blog',
                'url' => 'admin/blog',
                'can' => 'manage-blog',
            ],
            [
                'text' => ' Home',
                'url' => '',
                'icon' => 'fa fa-home',

            ],
            ['header' => 'Admins'],
            [
                'text' => ' Admin list',
                'url' => 'admins',
                'icon' => 'fa fa-user',

            ],
            ['header' => 'Users'],
            [
                'text' => ' User list',
                'url' => 'users',
                'icon' => 'fa fa-users',

            ],
            ['header' => 'Reviews'],
            [
                'text' => ' Reviews',
                'url' => 'reviews',
                'icon' => 'fa fa-comments',

            ],
            // 'label' => 4,
            // 'label_color' => 'success',
            // " style="color: #f2f2f2;"></i>

            // ['header' => 'labels'],
            // [
            //     'text' => 'important',
            //     'icon_color' => 'red',
            //     'url' => '#',
            // ],
            // [
            //     'text' => 'warning',
            //     'icon_color' => 'yellow',
            //     'url' => '#',
            // ],
            // [
            //     'text' => 'information',
            //     'icon_color' => 'cyan',
            //     'url' => '#',
            // ],
        ];
    }
}