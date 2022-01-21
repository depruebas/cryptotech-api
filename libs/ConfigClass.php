<?php

#  Clase para leer ficheros de configuración 
#
#  Formato de entrada:
#       directorio.fichero.parametro-del-array
#
#  return array;
#

class ConfigClass
{

  public static $items = [];

  public static function load( $filepath = [])
  {
    static::$items = include(  dirname( dirname(__FILE__)).'/' . $filepath[0] . '/' . $filepath[1] . '.php');
  }

  public static function get($key = null)
  {

    $input = explode('.', $key);
    $filepath[0] = $input[0];
    $filepath[1] = $input[1];
    unset( $input[0]);
    unset( $input[1]);
    $key = implode( '.', $input);

    static::load( $filepath);

    if ( !empty( $key))
    {
      return static::$items[ $key];
    }

    return static::$items;
    
  }

}