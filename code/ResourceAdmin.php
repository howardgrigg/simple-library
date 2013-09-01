<?php
class ResourceAdmin extends ModelAdmin {
  public static $managed_models = array('Resource', 'Issue', "Holding"); 
  static $url_segment = 'resources';
  static $menu_title = 'Library Admin';
  static $model_importers = array();
}