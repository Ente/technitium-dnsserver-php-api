<?php
namespace Technitium\DNSServer\API;
class allowed extends API {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `list()` - Returns a list of all allowed domains.
     * @param string $domain The domain to list.
     * @param string $direction The direction to list the domains. Valid values are [`up`, `down`].
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function list(string $domain = "", string $direction = "up"): array|bool{
        $response = $this->API->sendCall(["domain" => $domain, "direction" => $direction], "allowed/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `add()` - Adds a domain to the allowed list.
     * @param string $domain The domain to add.
     * @return bool Returns `true` if the domain was added successfully.
     */
    public function add(string $domain): bool{
        $response = $this->API->sendCall(["domain" => $domain], "allowed/add");
        return $response["status"] == "ok";
    }

    /**
     * `delete()` - Deletes a domain from the allowed list.
     * @param string $domain The domain to delete.
     * @return bool Returns `true` if the domain was deleted successfully.
     */
    public function delete(string $domain): bool{
        $response = $this->API->sendCall(["domain" => $domain], "allowed/delete");
        return $response["status"] == "ok";

    }

    /**
     * `flush()` - Flushes the allowed list.
     * @return bool Returns `true` if the allowed list was flushed successfully.
     */
    public function flush(): bool{
        $response = $this->API->sendCall([], "allowed/flush");
        return $response["status"] == "ok";

    }

    /**
     * `import()` - Imports a list of allowed domains.
     * @param array $zones The list of zones to import.
     * @return bool Returns `true` if the zones were imported successfully.
     */
    public function import(array $zones): bool{
        $response = $this->API->sendCall(["allowedZones" => implode(",", $zones)], "allowed/import");
        return $response["status"] == "ok";

    }

    public function export(){
        return $this->API->sendCall([], "allowed/export");
    }
}
