<?php
namespace Technitium\DNSSERVER\API\Helper;

class Log extends \Exception {
    public function __construct($message, $code = 0, \Exception $previous = null){
        $this->error_rep($message);
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public static function error_rep($message, $method = NULL){
        $error_file = self::logrotate(); // file on your fs, e.g. /var/www/html/error.log
        $version = @file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/VERSION") ?? "N/A"; //optional value
        if($method == NULL){
            $method = $_SERVER["REQUEST_METHOD"];
        }
        $addr = @$_SERVER["SERVER_ADDR"] ?? "N/A";
        $rhost = @$_SERVER["REMOTE_HOST"] ?? "N/A";
        $time = date("[d.m.Y | H:i:s]");
        error_log("{$time} \"{$message}\"\nURL: {$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]} \nVersion: {$version} Server IP:{$addr} - Server Name: {$_SERVER["SERVER_NAME"]} - Request Method: '{$method}'\nRemote Addresse: {$_SERVER["REMOTE_ADDR"]} - Remote Name: '{$rhost}' - Remote Port: {$_SERVER["REMOTE_PORT"]}\nScript Name: '{$_SERVER["SCRIPT_FILENAME"]}'\n=======================\n", 3, $error_file);
    
    }

    public static function getSpecificLogFilePath($date = null){
        if($date == null){
            $date = date("Y-m-d");
            return __DIR__ . "/data/logs/log-{$date}.log";
        } else {
            preg_match("/^\d{4}-\d{2}-\d{2}$/m", $date, $match);
            if($match[0] == null){
                self::getSpecificLogFilePath();
            }
            Log::error_rep("Trying to get log file for date '$date'");
            return __DIR__ . "/data/logs/log-{$match[0]}.log";
        }
    }

    public static function logrotate(){
        $logpath = __DIR__ . "/data/logs/";
        $date = date("Y-m-d");
        $lastrotate = @file_get_contents(__DIR__ . "/data/logs/logrotate-cache.txt");

        if($date != $lastrotate){
            if(!file_exists($logpath . "log-{$date}.log")){
                $newlog = $logpath . "log-{$date}.log";
                file_put_contents($newlog, "");
                file_put_contents(__DIR__ . "/data/logs/logrotate-cache.txt", $date);
            }
            return __DIR__ . "/data/logs/log-{$date}.log";
        }
        return __DIR__ . "/data/logs/log-{$date}.log";
    }
}
