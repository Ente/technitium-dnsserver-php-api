<?php
namespace Technitium\DNSServer\API\users;

class profile {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `get()` - Get user profile information
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function get(){
        $response = $this->API->sendCall([], "users/profile/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `set()` - Set user profile information
     * @param string $displayName Display name to be set.
     * @param int $sessionTimeoutSeconds Session timeout in seconds.
     * @return bool Returns `true` if profile was set successfully.
     */
    public function set(string $displayName = "", int $sessionTimeoutSeconds = 1800){
        $response = $this->API->sendCall(["displayName" => $displayName, "sessionTimeoutSeconds" => $sessionTimeoutSeconds], "users/profile/set");
        return $response["status"] == "ok";
    }
}
