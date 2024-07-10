<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <div 
        id="m_ver_menu" 
        class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " 
        data-menu-vertical="true"
        data-menu-scrollable="false" data-menu-dropdown-timeout="500"  
        >
        <input id="current-route" type="hidden" value="">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <?php
            $authUser = \Session::get('loginUser'); 
            $menu_items = config('dashboard_leftnavbar');          
            $privileges = isset($authUser['privilege_status']) && $authUser['privilege_status'] == "A" ? explode(",",$authUser['privileges']) : [];
           
            if(count($menu_items) > 0){
                foreach ($menu_items as $app){ 
            ?>
                <li class="m-menu__item @if(count($app['model_list']) > 0)m-menu__item--submenu parent-menu @endif {{getSidebarMenuActive($app['sidebar_menu_active'], $sidebar_menu_active, count($app['model_list']))}}" aria-haspopup="true" data-menu-submenu-toggle="hover">
                    <a id="{{$app['vue_route_name']}}" href="@if(!empty($app['url'])){{url($app['url'])}}@else#@endif"
                        class="m-menu__link @if(!empty($app['model_list']))m-menu__toggle @endif" >
                        <i class="m-menu__link-icon {{$app['icon']}}"></i>
                        <span class="m-menu__link-text">
                            {{$app['title']}}
                        </span>
                        @if(count($app['model_list']) > 0)
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        @endif
                    </a>
                    @if(count($app['model_list']) > 0)
                        <div class="m-menu__submenu">
                            <span class="m-menu__arrow"></span>
                            <ul class="m-menu__subnav">
                                <li class="m-menu__item  m-menu__item--parent " aria-haspopup="true">
                                    <a href="#" class="m-menu__link ">
                                        <span class="m-menu__link-text">
                                            {{$app['title']}}
                                        </span>
                                    </a>
                                </li>
                                @foreach ($app['model_list'] as $model)
                                <li class="m-menu__item @if(count($model['view_list']) > 0 )m-menu__item--submenu sub-menu @endif model-{{ $model['name'] }} {{getSidebarSubMenuActive($model['sidebar_submenu_active'], $sidebar_submenu_active, count($model['view_list']))}}" aria-haspopup="true">
                                        <a id="{{$model['vue_route_name']}}" href="@if(!empty($model['url'])){{url($model['url'])}}@else#@endif" 
                                            class="m-menu__link @if(!empty($model['view_list']))m-menu__toggle @endif" >
                                            <i class="m-menu__link-icon {{$model['icon']}}"></i>
                                            <span class="m-menu__link-text">
                                                {{ $model['title'] }}
                                            </span>
                                            @if(count($model['view_list']) > 0)
                                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                                            @endif
                                        </a>
                                        @if(count($model['view_list']) > 0)
                                        <div class="m-menu__submenu">
                                            <span class="m-menu__arrow"></span>
                                            <ul class="m-menu__subnav">
                                                @foreach ($model['view_list'] as $view)
                                                <li class="m-menu__item child-sub-menu {{getSidebarSubSubMenuActive($view['sidebar_subsubmenu_active'], $sidebar_subsubmenu_active)}}" aria-haspopup="true" >
                                                    <a id="{{$view['vue_route_name']}}"  href="@if(!empty($view['url'])){{url($view['url'])}}@else#@endif" class="m-menu__link ">
                                                        <i class="m-menu__link-bullet {{$view['icon']}}"></i>
                                                        <span class="m-menu__link-text">
                                                            {{ $view['title'] }}
                                                        </span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            <?php 
                }
            }
            ?>              
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
