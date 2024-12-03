<?php
namespace Technitium\DNSServer\API\apps;

class config {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `get()` - Returns the configuration of an app.
     * @param string $name The name of the app.
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function get(string $name): array|bool{
        $response = $this->API->sendCall(["name" => $name], "apps/config/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `set()` - Save the configuration of an app.
     * @return bool Returns `true` on success and `false` otherwise.
     */
    public function set(string $name): bool{
        $response = $this->API->sendCall(["name" => $name], "apps/config/set");
        return $response["status"] == "ok";
    }
}
