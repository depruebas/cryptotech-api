<?php

class MessagesClass
{

  private static function SetHttpHeader( $header)
  {
    header( $header);
  }

  # Metodo para grabar logs en disco
  public static function WriteLog( $data)
  {

    # Obtenemos la ruta de los logs del fichero de configuración
    $file = ConfigClass::get("config.config.ruta_logs")['general'] . (($data['success']) ? "trace.log" : date("Ym") . "_error.log");

    # Obtenemos la fecha actual
    $date = date( "Y-m-d H:i:s");

    # Grabamos log a disco
    error_log ( "[".$date."] ". $data['code'] . " - " . print_r( $data['message'], true). "\n", 3, $file);

  }

  public static function Response( $data)
  {
    # Establecemos el codigo de respuesta HTTP
    self::SetHttpHeader( $data['http_code']);

    # Graba log
    self::WriteLog( $data);

    # Devuelve datos a quien ha hecho la petición porque la salida siempre la hacemos
    # por la entrada, no paramos nunca el programa a menos que sea requerimiento
    return ( json_encode( $data, JSON_UNESCAPED_UNICODE));

  }

 }