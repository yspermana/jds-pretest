<?php

namespace App\Http\Middleware;

use Closure;

class LogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $response = $next($request);

        $this->_writeLog($response);

        return $response;

    }

    public function _writeLog($response){

        $logFile    = __DIR__ .'/../../../public/log/log.'. date('Ymd') .'.txt';

        if (file_exists($logFile)) {
           $openFile   = fopen($logFile, "a");
        }
        else{
           $openFile   = fopen($logFile, "w");
           chmod($logFile, 0777);
        }

        $userAgent  = empty($_SERVER['HTTP_USER_AGENT']) ? "Undetect" : $_SERVER['HTTP_USER_AGENT']; 

        $write      = "Request time : ". date('Y-m-d H:i:s') ."\n";
        $write     .= "Uri : ". $_SERVER['REQUEST_URI'] ."\n";
        $write     .= "Method : ". $_SERVER['REQUEST_METHOD'] ."\n";
        $write     .= "Ip : ". $_SERVER['REMOTE_ADDR'] ."\n";
        $write     .= "User agent : \n". $userAgent ."\n";
        $write     .= "Query : \n". $this->_getRequestQuery() ."\n";
        $write     .= "Response : \n". $response ."\n";
        $write     .= "\n\n--------------------------------------------------------------------------\n\n";

        fwrite($openFile, $write);
        fclose($openFile);

    }

    private function _getRequestQuery(){

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {

            $uri    = $_SERVER['REQUEST_URI'];
            $uris   = explode('?', $uri);
            $param  = sizeof($uris) > 1 ? $uris[1] : "";

            return $param;
        }

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
            return file_get_contents('php://input');
        }

        return "";

    }
}
