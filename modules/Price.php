<?php


Class Price 
{

	public function Init( $params)
	{

		if ( $params != "")
		{
			$parameters = explode( "&", $params);
		}
		else
		{
			$parameters[0] = "";
		}
		
		$options['url'] = ConfigClass::get("config.config.binance_url") . "?" . $parameters[0];

		$results_data = CURLRequest::Send( $options);

		if ( $results_data['info']['http_code'] == '200')
    {
    	return ( MessagesClass::Response( [
          'success' => true,
          'type' => 'INFO',
          'code' => 'API-STATUS-OK',
          'http_code' => ConfigClass::get("config.messages.200"),
          'message' => $results_data['data'],
        ]
      ));
    }
    else
    {
    	return ( MessagesClass::Response( [
          'success' => false,
          'type' => 'ERROR',
          'code' => 'API0003-' . CODE_ERROR,
          'http_code' => ConfigClass::get("config.messages.404"),
          'message' => ConfigClass::get("config.messages.API0003") . " - Remote-url: " . $results_data['info']['url'],
        ]
      ));
    }
	}

}