<?php
class ResourceAdmin extends ModelAdmin
{
    public static $managed_models = array('Resource', 'Issue', "Holding");
    public static $url_segment = 'resources';
    public static $menu_title = 'Library Admin';
    public static $model_importers = array();
    private static $menu_icon = 'library/img/admin-icon.png';
}
