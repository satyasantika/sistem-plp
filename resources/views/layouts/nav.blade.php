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

                    {{-- add MENU from database --}}
                    @foreach (myNav() as $menu)
                    @can($menu->url.'-read')
                    <li class="{{ request()->segment(2) == explode('/',$menu->url)[1] ? 'active' : '' }}">
                        <a href="{{ url($menu->url) }}" class="link">
                            {{-- <i class="ti-home"></i> --}}
                            <span>{{ $menu->name }}</span>
                        </a>
                    </li>
                    @endcan
                    @endforeach


                    {{-- add MENU from database --}}
                    {{-- @foreach (myNavigation() as $menu)
                    @can($menu->url.'-read')
                    <li class="{{ request()->segment(1) == $menu->url ? 'active open' : '' }}">
                        <a href="#" class="main-menu has-dropdown">
                            <i class="{{ $menu->icon }}"></i>
                            <span>{{ $menu->name }}</span>
                        </a>
                        <ul class="sub-menu {{ request()->segment(1) == $menu->url ? 'expand' : '' }}">
                            @foreach ($menu->children as $submenu)
                            @can($submenu->url.'-read')
                            <li class="{{ request()->segment(1) == explode('/',$submenu->url)[0] && request()->segment(2) == explode('/',$submenu->url)[1] ? 'active' : '' }}">
                                <a href="{{ url($submenu->url) }}" class="link"><span>{{ $submenu->name }}</span></a>
                            </li>
                            @endcan
                            @endforeach
                        </ul>
                    </li>
                    @endcan
                    @endforeach --}}

                </ul>
            </div>
        </nav>
