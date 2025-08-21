@php
    $menus = config('menus');
    $role_id = Auth::user()->role_id;

    //extracting the menus that is allowed to the current user
    $allowedMenus = [];
    foreach ($menus as $group_name => $menu_items) {
        foreach ($menu_items as $menu_item) {
            if (empty($menu_item['allowed_roles']) || in_array($role_id, $menu_item['allowed_roles'])) {
                $allowedMenus[$group_name][] = $menu_item;
            }
        }
    }

    $activeParentMenu = '';
@endphp

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @foreach ($allowedMenus as $group_name => $menu_items)
            <li class="nav-heading">{{ $group_name }}</li>
            @foreach ($menu_items as $menu_item)
                {{-- @if (!empty($menu_item['allowed_roles']) && !in_array($role_id, $menu_item['allowed_roles']))
                    @continue
                @endif --}}
                @if (sizeof($menu_item['sub_menus']) == 0)
                    <li class="nav-item">
                        @php
                            $link = '#'; //by default

                            if(Route::has($menu_item['route'])){
                                if(!empty($menu_item['param'])){
                                    $link = Route($menu_item['route'], $menu_item['param']);
                                }
                                else{
                                    $link = Route($menu_item['route']);
                                }
                            }
                        @endphp

                        <a class="nav-link @if (request()->routeIs($menu_item['route'])) {{ 'active' }} @else {{ 'collapsed' }} @endif "
                            href="{{ $link }}">
                            <i class="{{ $menu_item['icon'] ?? 'bi bi-grid' }}"></i>
                            <span>{{ $menu_item['menu_label'] }}</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link collapsed" id="parent_{{ $menu_item['menu_name'] }}"
                            data-bs-target="#{{ $menu_item['menu_name'] }}" data-bs-toggle="collapse" href="#">
                            <i class="bi bi-menu-button-wide"></i><span>{{ $menu_item['menu_label'] }}</span><i
                                class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="{{ $menu_item['menu_name'] }}" class="nav-content collapse "
                            data-bs-parent="#sidebar-nav">
                            @foreach ($menu_item['sub_menus'] as $sub_menu_item)
                                @if (!empty($sub_menu_item['allowed_roles']) && !in_array($role_id, $sub_menu_item['allowed_roles']))
                                    @continue
                                @endif
                                @php
                                    if (request()->routeIs($sub_menu_item['route'])) {
                                        $activeParentMenu = 'parent_' . $menu_item['menu_name'];
                                    }

                                    //sublink
                                    $sub_menu_link = '#'; //by default

                                    if(Route::has($sub_menu_item['route'])){
                                        if(!empty($sub_menu_item['param'])){
                                            $sub_menu_link = Route($sub_menu_item['route'], $sub_menu_item['param']);
                                        }
                                        else{
                                            $sub_menu_link = Route($sub_menu_item['route']);
                                        }
                                    }

                                @endphp
                                <li>
                                    <a href="{{ $sub_menu_link }}"
                                        class="@if (request()->routeIs($sub_menu_item['route'])) {{ 'active' }} @endif">
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
    <script>
        var activeParentMenu = "{{ $activeParentMenu }}";
        if (activeParentMenu != "") {
            var menu = document.getElementById(activeParentMenu);
            if (menu) {
                menu.classList.remove("collapse");
            }
        }
    </script>

</aside><!-- End Sidebar-->
