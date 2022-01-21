<?php

  function debug( $var)
  {
    $debug = debug_backtrace();
    echo "<pre>";
    echo $debug[0]['file']." ".$debug[0]['line']."<br><br>";
    print_r($var);
    echo "</pre>";
    echo "<br>";
  }


  function RandomString( $n = 20)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';

    for ($i = 0; $i < 20; $i++)
    {
        $randstring .= strtolower( $characters[rand(0, strlen($characters)-1)]);
    }

    return $randstring.date( 'is');
  }