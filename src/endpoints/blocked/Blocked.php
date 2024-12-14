<?php
namespace Technitium\DNSServer\API;

class blocked extends API {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `add()` - Adds a domain to the blocked list.
     * @param string $domain The domain to add.
     * @return bool Returns `true` if the domain was added successfully.
     */
    public function add(string $domain): bool{
        $response = $this->sendCall(["domain" => $domain], "blocked/add");
        return $response["status"] == "ok";
    }

    /**
     * `list()` - Returns a list of all blocked domains.
     * @param string $domain The domain to list.
     * @param string $direction The direction to list the domains. Valid values are [`up`, `down`].
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function list(string $domain = "", string $direction = "up"): array|bool{
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
    public function delete(string $domain): bool{
        $response = $this->sendCall(["domain" => $domain], "blocked/delete");
        return $response["status"] == "ok";

    }

    /**
     * `export()` - Exports all blocked zones.
     * @return string|bool Either the file path where the blocked zones were exported to or `false` if the export failed.
     */
    public function export(): string|bool{
        $result = $this->API->downloadFile("blocked/export", false, []);

        if(!empty($result)){
            return $result;
        } else {
            return false;
        }
    }

    /**
     * `import()` - Imports a list of blocked domains.
     * @param array $zones The list of zones to import.
     * @return bool Returns `true` if the zones were imported successfully.
     */
    public function import(array $zones): bool{
        $response = $this->sendCall(["blockedZones" => implode(",", $zones)], "blocked/import");
        return $response["status"] == "ok";
    }

    /**
     * `flush()` - Flushes the blocked list.
     * @return bool Returns `true` if the blocked list was flushed successfully.
     */
    public function flush(): bool{
        $response = $this->sendCall([], "blocked/flush");
        return $response["status"] == "ok";

    }
    
}
