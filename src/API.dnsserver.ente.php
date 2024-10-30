<?php
namespace Technitium\DNSServer\API;
use Technitium\DNSServer\API\admin;
use Technitium\DNSServer\API\allowed;
use Technitium\DNSServer\API\apps;
use Technitium\DNSServer\API\blocked;
use Technitium\DNSServer\API\cache;
use Technitium\DNSServer\API\dashboard;
use Technitium\DNSServer\API\dhcp;
use Technitium\DNSServer\API\dnsClient;
use Technitium\DNSServer\API\settings;
use Technitium\DNSServer\API\users;
use Technitium\DNSServer\API\zones;
use Technitium\DNSServer\API\Helper\DDNS;
use Technitium\DNSServer\API\Helper\Log;
use \Dotenv\Dotenv;
use MirazMac\DotEnv\Writer;

class API {

    private $protocol;

    private $admin;
    private $allowed;
    private $apps;
    private $blocked;
    private $cache;
    private $dashboard;
    private $dhcp;
    private $dnsClient;
    private $settings;
    private $users;
    private $zones;
    private $ddns;

    private $log;

    private $conf;

    private $path;

    private $fullPath;

    private $env = [];

    public function __construct($confPath = null, $name = null){
        $this->loader();
        $this->loadConf($confPath, $name);
        $this->setProtocol();
    }

    private function setProtocol(){
        if($this->env["USE_HTTPS"] == "true"){
            $this->protocol = "https";
        } else {
            $this->protocol = "http";
        }
    }

    private function loadConf($path = null, $name = null){
        $this->conf = $name ?? ".env";
        $this->path = $path ?? $_SERVER["DOCUMENT_ROOT"];
        $this->fullPath = $this->path . "/" . $this->conf;
        if($path != null){
            $env = \Dotenv\Dotenv::createUnsafeMutable($this->path, $this->conf);
            Log::error_rep("Using .env file: " . $this->path . "/" . $this->conf);
            $env->load();
        } else {
            $env = \Dotenv\Dotenv::createUnsafeMutable($this->path);
            Log::error_rep("Using .env file: " . $this->path . "/.env");
            $env->load();
        }
        $env->required("API_URL");
        $env->required("USERNAME");
        $env->required("PASSWORD");
        $this->env = [
            "API_URL" => getenv("API_URL"),
            "USERNAME" => getenv("USERNAME"),
            "PASSWORD" => getenv("PASSWORD"),
            "USE_HTTPS" => getenv("USE_HTTPS"),
            "USE_POST" => @getenv("USE_POST"),
            "INCLUDE_INFO" => getenv("INCLUDE_INFO"),
            "TOKEN" => @getenv("TOKEN")
        ];

    }

    public function loader(){
        require_once __DIR__ . "/endpoints/admin/Admin.php";
        require_once __DIR__ . "/endpoints/allowed/Allowed.php";
        require_once __DIR__ . "/endpoints/apps/Apps.php";
        require_once __DIR__ . "/endpoints/blocked/Blocked.php";
        require_once __DIR__ . "/endpoints/cache/Cache.php";
        require_once __DIR__ . "/endpoints/dashboard/Dashboard.php";
        require_once __DIR__ . "/endpoints/dhcp/DHCP.php";
        require_once __DIR__ . "/endpoints/dnsClient/DnsClient.php";
        require_once __DIR__ . "/endpoints/settings/Settings.php";
        require_once __DIR__ . "/endpoints/users/Users.php";
        require_once __DIR__ . "/endpoints/zones/Zones.php";

        require_once __DIR__ . "/helper/DDNS.Helper.API.dnsserver.ente.php";
        require_once __DIR__ . "/helper/Log.Helper.API.dnsserver.ente.php";
    }

    public function sendCall($data, $endpoint, $method = "POST", $skip = false, $bypass = false){
        $c = curl_init();
        $endpoint = $this->prepareEndpoint($endpoint, $bypass);
        if($this->env["USE_POST"]){
            $method = "POST";
        }
        switch($method){
            case "POST":
                curl_setopt($c, CURLOPT_URL, $endpoint . $this->appendAuth($method,$skip));
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_POST, true);
                curl_setopt($c, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                $data = http_build_query($data);
                curl_setopt($c, CURLOPT_URL, $endpoint . "?" . $data . $this->appendAuth($method, $skip));
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                break;
            default:
                $data = http_build_query($data);
                curl_setopt($c, CURLOPT_URL, $endpoint . "?" . $data . $this->appendAuth($method, $skip));
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                break;
        }
        try {
            $response = curl_exec($c);
            if(!$response){
                Log::error_rep("Failed to send request: " . curl_error($c));
                return ["status" => "error", "error" => curl_error($c)];
            }
        } catch (\Throwable $e){
            Log::error_rep("Failed to send request: " . $e->getMessage());
            return ["status" => "error", "error" => $e->getMessage()];
        }
        curl_close($c);
        if($this->checkResponse($response)){
            Log::error_rep("Successfully accessed endpoint: " . $endpoint);
            return json_decode($response, true);
        }
        return [
            "error" => "An error occurred",
        ];
    }

    private function appendAuth($m = "POST", $skip = false){
        $this->loadConf($this->path, $this->conf);
        if($skip){
            return "";
        }
        if(!empty($this->env["TOKEN"])){
            switch($m){
                case "POST":
                    return "?token=" . @$this->env["TOKEN"];
                case "GET":
                    return "&token=" . @$this->env["TOKEN"];
                }
        } else {
                $this->getPermanentToken();
                $this->loadConf($this->path, $this->conf);
                switch($m){
                    case "POST":
                        return "?token=" . @$this->env["TOKEN"];
                    case "GET":
                        return "&token=" . @$this->env["TOKEN"];
                }
        }
    }

    private function getPermanentToken(){
        Log::error_rep("Getting permanent token... | .env: " . $this->fullPath);
        $response = $this->sendCall([
            "user" => $this->env["USERNAME"],
            "pass" => $this->env["PASSWORD"],
            "tokenName" => "technitium-dnsserver-php-api"
        ], "user/createToken", "POST", true);
        $writer = new Writer($this->fullPath);
        try {
            $writer
            ->set("TOKEN", $response["token"], true)
            ->set("USERNAME", $this->env["USERNAME"], true)
            ->set("PASSWORD", $this->env["PASSWORD"], true)
            ->set("USE_HTTPS", $this->env["USE_HTTPS"], true)
            ->set("API_URL", $this->env["API_URL"], true)
            ->set("USE_POST", $this->env["USE_POST"], true)
            ->set("INCLUDE_INFO", $this->env["INCLUDE_INFO"], true)
            ->write(true);

        } catch(\Throwable $e){
            Log::error_rep("Unable to write to .env file: " . $this->fullPath);        }
        return true;
    }

    private function checkResponse($response){
        if(is_null($response)){
            return false;
        } else {
            $re = json_decode($response, true)["status"];
            return $re != null || $re !== "error" || $re !== "invalid-token";
        }
    }

    private function prepareEndpoint($endpoint, $bypass = false){
        if($bypass){
            return $this->protocol . "://" . $this->env["API_URL"] . "/api/" . $endpoint;
        }
        $endpoints = json_decode(file_get_contents(__DIR__ . "/helper/endpoints.json"));

        if(in_array($endpoint, $endpoints)){
            return $this->protocol . "://" . $this->env["API_URL"] . "/api/" . $endpoint;
        } else {
            return false;
        }
    }


    public function admin(): admin {
        if(!$this->admin) $this->admin = new admin($this);
        return $this->admin;
    }


    public function allowed(): allowed {
        if(!$this->allowed) $this->allowed = new allowed($this);
        return $this->allowed;
    }

    public function apps(): apps {
        if(!$this->apps) $this->apps = new apps($this);
        return $this->apps;
    }

    public function blocked(): blocked {
        if(!$this->blocked) $this->blocked = new blocked($this);
        return $this->blocked;
    }

    public function cache(): cache {
        if(!$this->cache) $this->cache = new cache($this);
        return $this->cache;
    }


    public function dashboard(): dashboard {
        if(!$this->dashboard) $this->dashboard = new dashboard($this);
        return $this->dashboard;
    }

    public function dhcp(): dhcp {
        if(!$this->dhcp) $this->dhcp = new dhcp($this);
        return $this->dhcp;
    }

    public function dnsClient(): dnsClient {
        if(!$this->dnsClient) $this->dnsClient = new dnsClient($this);
        return $this->dnsClient;
    }

    public function settings(): settings {
        if(!$this->settings) $this->settings = new settings($this);
        return $this->settings;
    }

    public function users(): users {
        if(!$this->users) $this->users = new users($this);
        return $this->users;
    }

    public function zones(): zones {
        if(!$this->zones) $this->zones = new zones($this);
        return $this->zones;
    }

    public function ddns(): DDNS {
        if(!$this->ddns) $this->ddns = new DDNS($this);
        return $this->ddns;
    }

    public function log(): Log {
        if(!$this->log) $this->log = new Log($this);
        return $this->log;
    }
}
