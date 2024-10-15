<?php
namespace Technitium\DNSServer\API;

class blocked extends API {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `add()` - Adds a domain to the blocked list.
     * @param string $domain The domain to add.
     * @return bool Returns `true` if the domain was added successfully.
     */
    public function add(string $domain){
        $response = $this->sendCall(["domain" => $domain], "blocked/add");
        return $response["status"] == "ok";
    }

    /**
     * `list()` - Returns a list of all blocked domains.
     * @param string $domain The domain to list.
     * @param string $direction The direction to list the domains. Valid values are [`up`, `down`].
     * @return array|bool Returns the result array or `false` if the group was not found. 
     */
    public function list(string $domain = "", string $direction = "up"){
        $response = $this->sendCall(["domain" => $domain, "direction" => $direction], "blocked/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `delete()` - Deletes a domain from the blocked list.
     * @param string $domain The domain to delete.
     * @return bool Returns `true` if the domain was deleted successfully.
     */
    public function delete(string $domain){
        $response = $this->sendCall(["domain" => $domain], "blocked/delete");
        return $response["status"] == "ok";

    }

    
    public function export($data){
        return $this->sendCall($data, "blocked/export");
    }

    /**
     * `import()` - Imports a list of blocked domains.
     * @param array $zones The list of zones to import.
     * @return bool Returns `true` if the zones were imported successfully.
     */
    public function import(array $zones){
        $response = $this->sendCall(["blockedZones" => implode(",", $zones)], "blocked/import");
        return $response["status"] == "ok";
    }

    /**
     * `flush()` - Flushes the blocked list.
     * @return bool Returns `true` if the blocked list was flushed successfully.
     */
    public function flush(){
        $response = $this->sendCall([], "blocked/flush");
        return $response["status"] == "ok";

    }
    
}
