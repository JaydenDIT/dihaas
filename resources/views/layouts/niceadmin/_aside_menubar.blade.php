@php
    $menus = config('menus');
    $role_id = Auth::user()->role_id;
@endphp

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @foreach ($menus as $group_name => $menu_items)
            <li class="nav-heading">{{ $group_name }}</li>
            @foreach ($menu_items as $menu_item)
                @if (!empty($menu_item['allowed_roles']) && !in_array($role_id, $menu_item['allowed_roles']))
                    @continue
                @endif
                @if (sizeof($menu_item['sub_menus']) == 0)
                    <li class="nav-item">
                        <a class="nav-link collapsed"
                            href="{{ Route::has($menu_item['route']) ? Route($menu_item['route']) : '#' }}">
                            <i class="{{ $menu_item['icon'] ?? 'bi bi-grid' }}"></i>
                            <span>{{ $menu_item['menu_label'] }}</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-target="#{{ $menu_item['menu_name'] }}"
                            data-bs-toggle="collapse" href="#">
                            <i class="bi bi-menu-button-wide"></i><span>{{ $menu_item['menu_label'] }}</span><i
                                class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="{{ $menu_item['menu_name'] }}" class="nav-content collapse "
                            data-bs-parent="#sidebar-nav">
                            @foreach ($menu_item['sub_menus'] as $sub_menu_item)
                                @if (!empty($sub_menu_item['allowed_roles']) && !in_array($role_id, $sub_menu_item['allowed_roles']))
                                    @continue
                                @endif
                                <li>
                                    <a href="{{ Route::has($sub_menu_item['route']) ? $sub_menu_item['route'] : '#' }}">
                                        <i class="bi bi-circle"></i><span>{{ $sub_menu_item['menu_label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        @endforeach
    </ul>

</aside><!-- End Sidebar-->
