<?php


Class APIManager
{

	public function Init()
	{

		$uri = explode( "/", substr( $_SERVER['REQUEST_URI'], 1, strlen( $_SERVER['REQUEST_URI'])));

		# Comprobamos que con la ruta viene la versión de la API que utilizamos
		if ( ConfigClass::get("config.config.api_version") != $uri['0'])
		{

			return ( MessagesClass::Response( [
	        'success' => false,
	        'type' => 'ERROR',
	        'code' => 'AUTH0001-' . CODE_ERROR,
	        'http_code' => 'HTTP/1.1 404  Not Found',
	        'message' => ConfigClass::get("config.messages.AUTH0001"),
	      ]
	    ));

		}

		## MODIFICACION PARA CRYPTOTECHFIN
		## NO HAY AUTENTIFICACION EN ESTA PRUEBA
		##
		
				/*$auth = new Authorization();

				# Validamos la api con el access_token o api_key.
				# Si la ruta que viene es oauth entonces la API solicita un access_token con el api_key

				if ( $uri[1] == 'oauth')
				{

					# Pasamos el POST que son los datos que tenemos que validar
					# Y devolvemos el resultado al cliente que inicia la petición porque devuelve un false con un error
					# o un true con un access_code
					return( $auth->ValidateKeys( $_POST));

				}


				# Si la uri es cualquier otra cosa que no oauth entonces sobre entendemos que viene en los headrs
				# un access_code y lo vamos a verificar

				# Obtenemos los headers de la peticion
				$headers = getallheaders();

		    # Eliminamoslos headers que no vamos a tratar
		    $keys = ConfigClass::get("config.config.keys");
		    foreach ( $headers as $key => $value)
		    {

		      if ( !in_array( $key, $keys))
		      {
		        unset ( $headers[$key]);
		      }

		    }

		    # Validamos el access_token que nos enviarn
		    $isvalid_access_token = $auth->ValidateAccessToken( $headers);

		    if ( !$isvalid_access_token)
		    {

		    	return ( MessagesClass::Response( [
			        'success' => false,
			        'type' => 'ERROR',
			        'code' => 'AUTH0006-' . CODE_ERROR,
			        'http_code' => 'HTTP/1.1 401 Unauthorized',
			        'message' => ConfigClass::get("config.messages.AUTH0006"),
			      ]
			    ));

		    }
*/
		## FIN SECCION AUTHENTIFICACION

    # Si la validacion del access_token es correcta pasamos a procesar los metodos de acceso a que que nos piden
    # Obtenemos el metodo que se utiliza para llamar a la API, solo soportamos GET, POST, PUT y DELETE
    $method = $_SERVER['REQUEST_METHOD'];

    if ( !in_array( $method, ConfigClass::get("config.config.methods")))
    {

      return ( MessagesClass::Response( [
          'success' => false,
          'type' => 'ERROR',
          'code' => 'AUTH0002-' . CODE_ERROR,
          'http_code' => 'HTTP/1.1 405 Method Not Allowed',
          'message' => str_replace( "#", $method, ConfigClass::get("config.messages.AUTH0002")),
        ]
      ));

    }


    # Extraemos la ruta del modulo a cargar
    $module = explode( "?", $uri[1]);

		# Obtenemos el fichero a cargar de la ruta que nos pasan
		$route = explode( "/", ConfigClass::get("config.routes.".$method)[$module[0]]);
    
    $_class = $route[0];
    $_method = $route[1];
    $_params = $module[1];


    # Cargamos la clase (fichero) que vamos a utilizar dinamicamente
    $class_include = dirname( dirname(__FILE__))."/modules/".$_class.".php";

    if ( file_exists( $class_include))
    {

      require $class_include;

      $action = new $_class();
      return ( $action->{$_method} ( $_params));
      
    }
    else
    {
    	return ( MessagesClass::Response( [
          'success' => false,
          'type' => 'ERROR',
          'code' => 'API0002-' . CODE_ERROR,
          'http_code' => ConfigClass::get("config.messages.404"),
          'message' => ConfigClass::get("config.messages.API0002"),
        ]
      ));
    }

	}

}