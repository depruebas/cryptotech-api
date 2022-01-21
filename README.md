# cryptotech-api

<b>Needs</b> write permissions logs directory chmod 777 logs/ -R

<b>Usage</b> http://cryptotech.depruebas.com/v1/price?symbol=BTCUSDT

<b>to test it local</b> need install php and php-curl extension, and execute php server 

<code>php -S localhost:8080</code>

and go to browser and launch http://localhost:8080/v1/price?symbol=BTCUSDT

if not send parameters as symbol=BTCUSDT return all results. The symbol (BTCUSDT) can be any that is in binance as BTCUSDT, ETHBUSD, ROSEBUSD ... 


<b>Returns</b>, always return json with the same structure 

<b>Result OK</b>

{
success: true,
type: "INFO",
code: "API-STATUS-OK",
http_code: "HTTP/1.1 200 OK",
message: "{"symbol":"BTCUSDT","price":"38790.41000000"}"
}


<b>Result Error</b>

{
success: false,
type: "ERROR",
code: "AUTH0001-RQM0OWWL8HSWEKNISMFP4113",
http_code: "HTTP/1.1 404 Not Found",
message: "La versión de la API no es correcta."
}
