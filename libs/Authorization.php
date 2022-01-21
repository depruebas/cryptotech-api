<?php

class Authorization
{


  public function ValidateKeys( $data_keys)
  {

  	# Validamos que lleguen todas las claves para la validación
    # En el array keys guardamos los nombres de los campos que tienen que llegar en un POST si o si
    # para validar la API
  	$access = true;
  	$keys = ConfigClass::get("config.config.keys");
  	unset( $keys[5]);


  	foreach ( $keys as $key)
  	{

  		if ( !in_array( $key,  array_keys( $data_keys)))
  		{
  			$access = false;
			 	break;

  		}
  		else
  		{

  			if ( $data_keys[ $key] == "")
  			{
  				$access = false;
  				break;
  			}

  		}

  	}

    # No hemos pasado la validacion de las claves
  	if ( !$access)
		{
			return ( MessagesClass::Response( [
          'success' => false,
          'type' => 'ERROR',
          'code' => 'AUTH0003-' . CODE_ERROR,
          'http_code' => 'HTTP/1.1 403 Forbidden',
          'message' => ConfigClass::get("config.messages.AUTH0003"),
        ]
      ));
		}


		# Fin validacion vienen todos los datos que necesitamos
    #
		# Validamos los datos de acceso a la API

  	$params['query'] = "SELECT a.id as env_id, c.id as user_id, c.username
                          FROM `auth_environments` as a
                          INNER JOIN auth_users_env as b On b.env_id = a.id
                          INNER JOIN auth_users as c On b.user_id = c.id
                          WHERE a.api_key = ? And a.api_key_secret = ? And a.enabled = 1  And a.api_name = ?
                            And c.username = ? And c.password = ? And c.enabled = 1";
    $params['params'] = [ $data_keys['api_key'],
													$data_keys['api_key_secret'],
													$data_keys['api_name'],
													$data_keys['username'],
													$data_keys['password'],
    										];
    $rows = PDOManager::ExecuteQuery( $params);


    if ( !empty( $rows['data']))
    {

    	# Creamos el access_token
    	$date_now = date("Y-m-d H:i:s");
    	$date_expired = date("Y-m-d H:i:s", (strtotime(date($date_now)) + ConfigClass::get("config.config.expires_token")));

    	$data = $rows['data'][0]['id'] . date("YmdHis") . RandomString( 60);
			$access_token =  base64_encode( hash_hmac( 'sha1', $data,  $data_keys['api_key'] , false));

			$data_acces_token['table'] = "auth_access_tokens";
			$data_acces_token['fields'] = [
			  'env_id' => $rows['data'][0]['env_id'],
        'user_id' => $rows['data'][0]['user_id'],
			  'api_key' => $data_keys['api_key'],
			  'access_token' => $access_token,
			];

			# Call to Insert method
			$ret = PDOManager::Insert( $data_acces_token);


			if ( $ret['success'])
			{
				return ( MessagesClass::Response( [
		        'success' => true,
		        'type' => 'RESULT',
            'http_code' => 'HTTP/1.1 200 OK',
		        'data' => $access_token,
		      ]
		    ));

			}
    	else
    	{

    		return ( MessagesClass::Response( [
		        'success' => false,
		        'type' => 'ERROR',
		        'code' => 'AUTH0004-' . CODE_ERROR,
            'http_code' => 'HTTP/1.1 403 Forbidden',
		        'message' => ConfigClass::get("config.messages.AUTH0004"),
		      ]
		    ));

    	}

    }
    else
    {

    	return ( MessagesClass::Response( [
	        'success' => false,
	        'type' => 'ERROR',
	        'code' => 'AUTH0005-' . CODE_ERROR,
          'http_code' => 'HTTP/1.1 403 Forbidden',
	        'message' => ConfigClass::get("config.messages.AUTH0005"),
	      ]
	    ));

    }

  }

  public function ValidateAccessToken( $data_keys)
  {

  	if ( !isset( $data_keys[ 'api_key']) Or $data_keys['api_key'] == '')
		{

			return ( false);

	  }

	  if ( !isset( $data_keys[ 'access_token']) Or $data_keys['access_token'] == '')
		{

			return ( false);

	  }

	  $params['query'] = "SELECT `id` FROM `auth_access_tokens`
  										WHERE `api_key` = ? And `access_token` = ?  order by id desc limit 1";
    $params['params'] = [ $data_keys['api_key'],	$data_keys['access_token']];


    $rows = PDOManager::ExecuteQuery( $params);

    if ( !$rows['success'])
    {
    	return( false);
    }
    else
    {

    	if ( !empty( $rows['data']))
    	{
    		return ( true);
    	}
    	else
    	{
    		return ( false);
    	}

    }

  }


}