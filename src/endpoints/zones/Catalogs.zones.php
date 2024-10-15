<?php
namespace Technitium\DNSServer\API\zones;

class catalogs {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `list()` - List all available zone catalogs
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function list($data){
        $response = $this->API->sendCall($data, "zones/catalogs/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }
}
