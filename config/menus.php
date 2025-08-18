<?php
return [
    'main'    => [
        [
            'menu_label'    => 'Dashboard',
            'menu_name'     => 'dashboard',
            'route'         => 'home',
            'allowed_roles' => [], //arbitrary, anyone is allowed
            'sub_menus'     => [],
            'displayOrder'  => 1,
            'icon'          => 'bi bi-grid',
        ],
    ],

    'admin'   => [
        [
            'menu_label'    => 'Process',
            'menu_name'     => 'process',
            'route'         => 'admin.process.index',
            'allowed_roles' => [999], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 2,
            'icon'          => 'bi bi-menu-button-wide',
        ],
        [
            'menu_label'    => 'Create Process',
            'menu_name'     => 'create_process',
            'route'         => 'admin.process.create',
            'allowed_roles' => [999], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 2,
            'icon'          => 'bi bi-layout-text-window-reverse',
        ],
        [
            'menu_label'    => 'Role',
            'menu_name'     => 'role',
            'route'         => 'admin.role.index',
            'allowed_roles' => [999], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 3,
            'icon'          => 'bi bi-journal-text',
        ],
        [
            'menu_label'    => 'Task',
            'menu_name'     => 'task',
            'route'         => 'admin.task.index',
            'allowed_roles' => [999], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 4,
            'icon'          => 'bi bi-journal-text',
        ],
        [
            'menu_label'    => 'Create Task',
            'menu_name'     => 'create_task',
            'route'         => 'admin.task.create',
            'allowed_roles' => [999], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 4,
            'icon'          => 'bi bi-journal-text',
        ],
        [
            'menu_label'    => 'Process Task Mapping',
            'menu_name'     => 'process_task_mapping',
            'route'         => 'admin.processtaskmapping.index',
            'allowed_roles' => [999], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 5,
        ],
        [
            'menu_label'    => 'All My Process',
            'menu_name'     => 'all_my_process',
            'route'         => 'tasks.performa.all',
            'allowed_roles' => [999], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 6,
        ],

    ],

    'citizen' => [
        [
            'menu_label'    => 'Submit Application',
            'menu_name'     => 'submit_application',
            'route'         => 'duties.proforma.create',
            'allowed_roles' => [77], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 1,
            'icon'          => 'bi bi-journal-text',
        ],
    ],

];
