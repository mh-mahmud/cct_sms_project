<?php

if (! function_exists('getSidebarMenuActive')) {
    /**
     * Menu active.
     *
     * @param  string  $class
     * @return string
     */
    function getSidebarMenuActive($menu_context, $menu_view, $model_count)
    {
        $css_class = "";

        if($menu_context == $menu_view && $model_count > 0)
            $css_class = 'm-menu__item--open m-menu__item--expanded';
        elseif ($menu_context == $menu_view && $model_count == 0)
            $css_class = 'm-menu__item--active';            

        return $css_class;
    }
}

if (! function_exists('getSidebarSubMenuActive')) {
    /**
     * Sub Menu active.
     *
     * @param  string  $class
     * @return string
     */
    function getSidebarSubMenuActive($menu_context, $menu_view, $model_count)
    {
        $css_class = "";

        if($menu_context == $menu_view && $model_count > 0)
            $css_class = 'm-menu__item--open m-menu__item--expanded';
        elseif ($menu_context == $menu_view && $model_count == 0)
            $css_class = 'm-menu__item--active';            

        return $css_class;
    }
}

if (! function_exists('getSidebarSubSubMenuActive')) {
    /**
     * Sub Sub Menu active.
     *
     * @param  string  $class
     * @return string
     */
    function getSidebarSubSubMenuActive($menu_context, $menu_view)
    {
        $css_class = "";

        if ($menu_context == $menu_view)
            $css_class = 'm-menu__item--active';            

        return $css_class;
    }
}