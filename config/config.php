<?php

  return [

    # Array de bases de datos, podemos tener multiples
    'database' => [

      'default' => [
        'dsn' => 'mysql:host=localhost;dbname=default;charset=utf8mb4',
        'username' => 'root',
        'password' => 'root',
      ],

    ],

    'binance_url' => 'https://api.binance.com/api/v1/ticker/price',

    # ruta de logs  de la aplicacion
    'ruta_logs' => [
      'general' =>   dirname( dirname(__FILE__)) . '/logs/',
      'error_log' =>  dirname( dirname(__FILE__)). '/logs/',
    ],

    # Verbos rest que acepta la API
    'methods' => [ 'GET', 'POST', 'PUT', 'DELETE'],

    # Keys necesarias para validar la API y el usuario que  hace la petición
    # En lugar de poner usuario y password podemos poner token y token_secret si en lugar
    # de usuarios estamos trabajando con dispositivos IoT
    'keys' => [ 'api_key', 'api_key_secret', 'username', 'password', 'api_name', 'access_token'],

    # 0 - no depuración / 1 - depuración
    'debug' => 0,

    # development or production
    'environment' => 'development',

    # API configuración
    'api_version' => 'v1',
    'api_enabled' => 1,
    'api_salt' => 'WQ5+VEy&*m&6qw12Ra!',

  ];
