<?php
return [
    'main'    => [
        [
            'menu_label'    => 'Dashboard',
            'menu_name'     => 'dashboard',
            'route'         => 'home',
            'allowed_roles' => [77], //arbitrary, anyone is allowed if empty array, 77 means for citizen
            'sub_menus'     => [],
            'displayOrder'  => 1,
            'icon'          => 'bi bi-grid',
        ],
        [
            'menu_label'    => 'Dashboard',
            'menu_name'     => 'department_dashboard',
            'route'         => 'tasks.performa.all',
            'allowed_roles' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 999], //Only for department users
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
        [
            'menu_label'    => 'Submitted Application',
            'menu_name'     => 'submit_application',
            'route'         => 'duties.form.index',
            'param'         => ['tasks_id' => 1],
            'allowed_roles' => [77], //Only superadmin
            'sub_menus'     => [],
            'displayOrder'  => 1,
            'icon'          => 'bi bi-journal-text',
        ],
    ],

];
