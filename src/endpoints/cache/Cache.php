<?php
namespace Technitium\DNSServer\API;

class cache extends API {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `list()` - List all cached zones.
     * @param string $domain Optional domain name to list records. Default: zone root
     * @param string $direction Optional direction of browsing the zone. Valid values are: ["up", "down"]
     * @return array|bool Returns the result array or `false` otherwise.
     */
    public function list(string $domain = "", string $direction = "up"): array|bool{
        $response = $this->sendCall(["domain" => $domain, "direction" => $direction], "cache/list");
        if($response["status" == "ok"]){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `delete()` - Delete a cached zone.
     * @param string $domain Domain name to delete records.
     * @return bool Returns `true` on success and `false` otherwise.
     */
    public function delete(string $domain = ""): bool{
        $response = $this->sendCall(["domain" => $domain], "cache/delete");
        return $response["status"] == "ok";
    }
}
