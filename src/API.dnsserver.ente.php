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

    public function __construct($confPath = null){
        $this->loader();
        $this->loadConf($confPath = null);
        $this->setProtocol();
    }

    public function setProtocol(){
        if($_ENV["USE_HTTPS"] == "true"){
            $this->protocol = "https";
        } else {
            $this->protocol = "http";
        }
    }

    public function loadConf($path = null){
        if($path != null){
            $env = \Dotenv\Dotenv::createImmutable($path);
            $env->safeLoad();
        } else {
            $env = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
            $env->safeLoad();
        }
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

    public function sendCall($data, $endpoint, $method = "POST"){
        $c = curl_init();
        $endpoint = $this->prepareEndpoint($endpoint);
        if($_ENV["USE_POST"]){
            $method = "POST";
        }
        switch($method){
            case "POST":
                curl_setopt($c, CURLOPT_URL, $endpoint . $this->appendAuth());
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_POST, true);
                curl_setopt($c, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                $data = http_build_query($data);
                curl_setopt($c, CURLOPT_URL, $endpoint . "?" . $data . $this->appendAuth());
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                break;
        }

        $response = curl_exec($c);
        if(!$response){
            return curl_error($c);
        }
        curl_close($c);
        if($this->checkResponse($response)){
            return json_decode($response, true);
        }
        return [
            "error" => "An error occurred",
        ];
    }

    public function appendAuth($m = "POST"){
        if($_ENV["TOKEN"] != ""){
            switch($m){
                case "POST":
                    return "?token=" . $_ENV["TOKEN"] . "&user=" . $_ENV["USERNAME"] . "&password=" . $_ENV["PASSWORD"];
                case "GET":
                    return "&token=" . $_ENV["TOKEN"] . "&user=" . $_ENV["USERNAME"] . "&password=" . $_ENV["PASSWORD"];
            }
        }
    }

    public function checkResponse($response){
        if(is_null($response)){
            return false;
        } else {
            $re = json_decode($response, true)["status"];
            return $re != null || $re !== "error" || $re !== "invalid-token";
        }
    }

    public function prepareEndpoint($endpoint){
        $endpoints = json_decode(file_get_contents(__DIR__ . "/helper/endpoints.json"));

        if(in_array($endpoint, $endpoints)){
            return $this->protocol . "://" . $_ENV["API_URL"] . "/api/" . $endpoint;
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
