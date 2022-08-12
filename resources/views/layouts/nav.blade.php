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
                    {{-- TODO: Add menu items --}}
                    <li class="{{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="link">
                            <i class="ti-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @foreach (myNavigation() as $menu)
                    <li class="{{ request()->segment(1) == $menu->url ? 'active open' : '' }}">
                        <a href="#" class="main-menu has-dropdown">
                            <i class="{{ $menu->icon }}"></i>
                            <span>{{ $menu->name }}</span>
                        </a>
                        <ul class="sub-menu {{ request()->segment(1) == $menu->url ? 'expand' : '' }}">
                            @foreach ($menu->children as $submenu)
                            <li class="{{ request()->segment(1) == explode('/',$submenu->url)[0] && request()->segment(2) == explode('/',$submenu->url)[1] ? 'active' : '' }}">
                                <a href="{{ url($submenu->url) }}" class="link"><span>{{ $submenu->name }}</span></a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                    {{-- <li>
                        <a href="#" class="main-menu has-dropdown">
                            <i class="ti-desktop"></i>
                            <span>UI Elements</span>
                        </a>
                        <ul class="sub-menu ">
                            <li><a href="element-ui.html" class="link"><span>Elements</span></a></li>
                            <li><a href="element-accordion.html" class="link"><span>Accordion</span></a></li>
                            <li><a href="element-tabs-collapse.html" class="link"><span>Tabs & Collapse</span></a></li>
                            <li><a href="element-card.html" class="link"><span>Card</span></a></li>
                            <li><a href="element-button.html" class="link"><span>Buttons</span></a></li>
                            <li><a href="element-alert.html" class="link"><span>Alert</span></a></li>
                            <li><a href="element-themify-icons.html" class="link"><span>Themify Icons</span></a></li>
                            <li><a href="element-modal.html" class="link"><span>Modal</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="main-menu has-dropdown">
                            <i class="ti-book"></i>
                            <span>Form</span>
                        </a>
                        <ul class="sub-menu ">
                            <li><a href="form-element.html" class="link">
                                    <span>Form Element</span></a>
                            </li>
                            <li><a href="form-datepicker.html" class="link">
                                    <span>Datepicker</span></a>
                            </li>
                            <li><a href="form-select2.html" class="link">
                                    <span>Select2</span></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="main-menu has-dropdown">
                            <i class="ti-notepad"></i>
                            <span>Utilities</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="error-404.html" target="_blank" class="link"><span>Error 404</span></a></li>
                            <li><a href="error-403.html" target="_blank" class="link"><span>Error 403</span></a></li>
                            <li><a href="error-500.html" target="_blank" class="link"><span>Error 500</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="main-menu has-dropdown">
                            <i class="ti-layers-alt"></i>
                            <span>Pages</span>
                        </a>
                        <ul class="sub-menu ">
                            <li><a href="pages-blank.html" class="link"><span>Blank</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="main-menu has-dropdown">
                            <i class="ti-write"></i>
                            <span>Tables</span>
                        </a>
                        <ul class="sub-menu ">
                            <li><a href="table-basic.html" class="link"><span>Table Basic</span></a></li>
                            <li><a href="table-datatables.html" class="link"><span>DataTables</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="charts.html" class="link">
                            <i class="ti-bar-chart"></i>
                            <span>Charts</span>
                        </a>
                    </li> --}}
                </ul>
            </div>
        </nav>
