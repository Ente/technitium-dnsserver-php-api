<?php
namespace Technitium\DNSServer\API;
use Technitium\DNSServer\API\dhcp\leases;
use Technitium\DNSServer\API\dhcp\scopes;

class dhcp extends API {
    public $API;

    private $leases;

    private $scopes;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
        $this->eloader();
    }

    private function eloader(){
        require_once __DIR__ . "/Leases.dhcp.php";
        require_once __DIR__ . "/Scopes.dhcp.php";
    }

    public function scopes(): scopes {
        if(!$this->scopes) $this->scopes = new scopes($this->API);
        return $this->scopes;
    }

    public function leases(): leases {
        if(!$this->leases) $this->leases = new leases($this->API);
        return $this->leases;
    }
}
