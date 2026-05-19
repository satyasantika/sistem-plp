        <nav class="main-sidebar ps-menu">
            <div class="sidebar-toggle action-toggle">
                <a href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
            <div class="sidebar-opener action-toggle">
                <a href="#">
                    <i class="ti-angle-right"></i>
                </a>
            </div>
            <div class="sidebar-header">
                <div class="text">PLP</div>
                <div class="close-sidebar action-toggle">
                    <i class="ti-close"></i>
                </div>
            </div>
            <div class="sidebar-content">
                <ul>
                    {{-- Default DASHBOARD MENU --}}
                    <li class="{{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="link">
                            <i class="ti-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @can('active-read')
                        @php
                            $menuItems = config('menu.items', []);
                            if (auth()->user()->hasRole('admin')) {
                                $menuItems = array_values(array_filter($menuItems, function ($menu) {
                                    return isset($menu['url'])
                                        && str_starts_with(ltrim((string) $menu['url'], '/'), 'konfigurasi/');
                                }));
                            }
                        @endphp
                        {{-- static MENU from config/menu.php (dibatasi otomatis ke /konfigurasi untuk role admin) --}}
                        @foreach ($menuItems as $menu)
                        @php
                            $menuAllowed = false;
                            if (isset($menu['permissions_any']) && is_array($menu['permissions_any'])) {
                                $menuAllowed = auth()->user()->canAny($menu['permissions_any']);
                            } elseif (isset($menu['permission'])) {
                                $menuAllowed = auth()->user()->can($menu['permission']);
                            }
                        @endphp
                        @if($menuAllowed)
                        @php($menuIcon = $menu['icon'] ?? 'ti-angle-right')
                        <li class="{{ request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'active' : '' }}">
                            <a href="{{ url($menu['url']) }}" class="link">
                                <i class="{{ $menuIcon }}"></i>
                                <span>{{ $menu['name'] }}</span>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    @endcan
                </ul>
            </div>
        </nav>
