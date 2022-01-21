<?php 

Class CURLRequest
{

	public static function Send( $data = [])
  {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $data['url']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");

    $curlData = curl_exec($curl);
    $info = curl_getinfo($curl);
    $error = curl_error($curl);

    curl_close($curl);

    return ( [ 
        'data' => $curlData,
        'info' => $info,
        'error' => $error,
      ]
    );

  }

}