<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

class tpl {
  
  static public $vars = array();

  static function set($key, $value=false) {
    if(is_array($key)) {
      self::$vars = array_merge(self::$vars, $key);
    } else {
      self::$vars[$key] = $value;
    }
  }

  static function get($key=null, $default=null) {
    if($key===null) return (array)self::$vars;
    return a::get(self::$vars, $key, $default);       
  }

  static function load($template='default.php', $vars=array(), $return=false) {
    $extension = pathinfo($template, PATHINFO_EXTENSION);
    $engines = c::get('tpl.engines');
    $engine = $engines[$extension];
    $file = c::get('root.templates') . '/' . $template;
    if ($engine)
      $file = $engine(basename($template, '.' . $extension));
    return self::loadFile($file, $vars, $return);
  }
  
  static function loadFile($file, $vars=array(), $return=false) {
    if(!file_exists($file)) return false;
    if(!is_array($vars)) {
        $vars = array();
    }
    @extract(self::$vars);
    @extract($vars);
    content::start();
    require($file);
    return content::end($return); 
  }

}

