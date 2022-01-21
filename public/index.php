<?php

	# Iniciamos el timer para ver el tiempo que tarda la api en procesar una peticíon
  $start_time = microtime(true);


  # Guardamos la fecha con la que se inicia la API en cada petición, despues la utilizaremos en los
  # logs para saber cuando fue la petición
  global $init_time;
  $init_time = date( "Y-m-d H:i:s");


  # Incluimos los headers para que
  # el content-type y el cache ( no queremos que se cacheen los resultados )
  header('Content-Type: text/html; charset=utf-8');
  header('Cache-Control: no-cache');
  header('Cache-Control: no-store');
  header('Pragma: no-cache');

	# Añadimos las librerias .php que vamos a necesitar en la API
  require_once dirname( dirname(__FILE__))."/libs/Utils.php";
  require_once dirname( dirname(__FILE__))."/libs/ConfigClass.php";
  require_once dirname( dirname(__FILE__))."/libs/CustomErrorLog.php";
  require_once dirname( dirname(__FILE__))."/libs/PDOManager.php";
  require_once dirname( dirname(__FILE__))."/libs/MessagesClass.php";
  #require_once dirname( dirname(__FILE__))."/libs/Authorization.php";
  require_once dirname( dirname(__FILE__))."/libs/CURLRequest.php";
  require_once dirname( dirname(__FILE__))."/libs/APIManager.php";

  # Inicializamos CustomErrorLog, para procesar automaticamente los errores
  $e = new CustomErrorLog();


  # Defimos las costantes del programa
  define( 'DEBUG', ConfigClass::get("config.config.debug"));
  define( 'ENVIRONMENT', ConfigClass::get("config.config.environment"));
  define( "CODE_ERROR", strtoupper( RandomString( 10)));
  define( "EOF", "\n");


  # Abrimos la conexión a la base de datos para poder trabajar con ella en toda la aplicacion
	$_config = ConfigClass::get("config.config.database")['default'];

  $connection = PDOManager::Connection( $_config);

  # Instanciamos la clase Core que es la que verificaras las rutas, la autenficacion y las acciones
  # a realizar por la API
  $core = new APIManager();
  $return = $core->Init( $connection);

  $connection = null;

  # Enviamos el resultado de la de proceso de la API a quien ha hecho la peticion
  echo $return;


  # Si tenemos la depuración activada se registra el tiempo que tarda en procesar las peticiones
  if ( DEBUG)
  {
    $time = microtime(true) - $start_time;

    if ( $time > 1)
    {
      # formatPeriod es una función que esta en el fichero Utils.php
      $time = formatPeriod( $time);
    }

    # Esto dara error de json mal formado si la respuesta de la API la trata una aplicación que
    # espera un json, si usamos curl o postman podemos ver los tiempos que tarda la API en procesar
    # las solicitudes.
    echo EOF.( $time ) . EOF;
  }


