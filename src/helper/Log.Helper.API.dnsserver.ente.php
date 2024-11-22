<?php
namespace Technitium\DNSSERVER\API\Helper;

class Log extends \Exception {

    /**
     * Log constructor.
     * @param string $message The exception message
     * @param int $code The exception code
     * @param \Exception|null $previous A reference to the previous exception
     */
    public function __construct($message, $code = 0, \Exception $previous = null){
        $trace = $this->getTraceAsString() ?? "N/A";
        $this->error_rep(message: $message, method: $trace);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * `error_rep()` function is used to log the error message to the error log file.
     * @param string $message The error message to be logged.
     * @param string $method Name of either the method name that called the function or the request method.
     */
    public static function error_rep($message, $method = null){
        $error_file = self::logrotate(); // file on your fs, e.g. /var/www/html/error.log
        $version = NULL;
        if($method == null){
            $method = @$_SERVER["REQUEST_METHOD"];
        }
        $uri = @$_SERVER["REQUEST_URI"];
        $host = @$_SERVER["HTTP_HOST"];
        $port = @$_SERVER["SERVER_PORT"];
        $script = @$_SERVER["SCRIPT_FILENAME"];
        $name = @$_SERVER["SERVER_NAME"] ?? "N/A";
        $addr = @$_SERVER["SERVER_ADDR"] ?? "N/A";
        $rhost = @$_SERVER["REMOTE_HOST"] ?? "N/A";
        $time = date("[d.m.Y | H:i:s]");
        error_log("{$time} \"{$message}\"\nURL: {$host}{$uri} \nVersion: {$version} Server IP:{$addr} - Server Name: {$name} - Request Method: '{$method}'\nRemote Addresse: {$addr} - Remote Name: '{$rhost}' - Remote Port: {$port}\nScript Name: '{$script}'\n=======================\n", 3, $error_file);
    
    }

    /**
     * `getSpecificLogFilePath()` function is used to get the specific log file path.
     * @param string|null $date The date to get the log file path for. Format: "Y-m-d".
     * @return string The specific log file path.
     */
    private static function getSpecificLogFilePath($date = null){
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

    /**
     * `logrotate()` function is used to rotate the log file.
     * @return string The path to the log file.
     */
    private static function logrotate(){
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
