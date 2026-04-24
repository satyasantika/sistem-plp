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
                        {{-- static MENU from config/menu.php --}}
                        @foreach (config('menu.items', []) as $menu)
                        @can($menu['permission'])
                        <li class="{{ request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'active' : '' }}">
                            <a href="{{ url($menu['url']) }}" class="link">
                                {{-- <i class="ti-home"></i> --}}
                                <span>{{ $menu['name'] }}</span>
                            </a>
                        </li>
                        @endcan
                        @endforeach
                    @endcan
                </ul>
            </div>
        </nav>
