<?php
namespace Technitium\DNSServer\API;
use Technitium\DNSServer\API\dashboard\stats;

class dashboard extends API {
    public $API;

    private $stats;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
        $this->eloader();
    }

    private function eloader(): void{
        require_once __DIR__ . "/Stats.dashboard.php";
    }

    public function stats(): stats {
        if(!$this->stats) $this->stats = new stats($this->API);
        return $this->stats;
    }
}
