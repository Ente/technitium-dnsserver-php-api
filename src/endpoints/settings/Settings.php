<?php
namespace Technitium\DNSServer\API;

class settings extends API {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    public function backup($data){
        return $this->API->sendCall($data, "settings/backup");
    }

    /**
     * `forceUpdateBlockLists()` - Forces an update of the block lists.
     * @return array|bool Returns the response from the server or `false` if the request failed.
     */
    public function forceUpdateBlockLists(){
        $response = $this->API->sendCall([], "settings/forceUpdateBlockLists");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `get()` - Returns the DNS server settings.
     * @return array|bool Returns the DNS server settings or `false` if the request failed.
     */
    public function get(){
        $response = $this->API->sendCall([], "settings/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `set()` - Sets the DNS server settings.
     * @param array $data An array of settings to set. Refer to the API documentation for the list of settings.
     * @return array|bool Returns the DNS server settings or `false` if the request failed.
     * @link https://github.com/TechnitiumSoftware/DnsServer/blob/master/APIDOCS.md
     */
    public function set($data){
        $response = $this->API->sendCall($data, "settings/set");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `getTsigKeyNames()` - Returns the list of TSIG keys.
     * @return array|bool Returns the list of TSIG keys or `false` if the request failed.
     */
    public function getTsigKeyNames(){
        $response = $this->API->sendCall([], "settings/getTsigKeyNames");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    public function restore($data){
        return $this->API->sendCall($data, "settings/restore");
    }

    /**
     * `temporaryDisableBlocking()` - Temporarily disables blocking for a specified number of minutes.
     * @param int $minutes The number of minutes to disable blocking for.
     * @return bool Returns `true` if the request was successful.
     */
    public function temporaryDisableBlocking(int $minutes){
        return $this->API->sendCall(["minutes" => $minutes], "settings/temporaryDisableBlocking");
    }
}
