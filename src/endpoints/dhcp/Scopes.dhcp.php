<?php
namespace Technitium\DNSServer\API\dhcp;

class scopes {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `list()` - Returns the list of DHCP scopes.
     * @return array|bool Returns the list of DHCP scopes or `false` if the request failed.
     */
    public function list(){
        $response = $this->API->sendCall([], "dhcp/scopes/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `get()` - Returns the DHCP scope details.
     * @param string $name The name of the scope.
     * @return array|bool Returns the DHCP scope details or `false` if the request failed.
     */
    public function get(string $name){
        $response = $this->API->sendCall(["name" => $name], "dhcp/scopes/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `set()` - Sets the DHCP scope details.
     * @param array $data An array of settings to set. Refer to the API documentation for the list of settings.
     * @return array|bool Returns the DHCP scope details or `false` if the request failed.
     * @link https://github.com/TechnitiumSoftware/DnsServer/blob/master/APIDOCS.md
     */
    public function set($data){
        $response = $this->API->sendCall($data, "dhcp/scopes/set");
        return $response["status"] == "ok";
    }

    /**
     * `addReservedLease()` - Adds a reserved lease to the DHCP scope.
     * @param string $name The name of the lease.
     * @param string $hardwareAddress The hardware address of the lease.
     * @param string $ipAddress The IP address of the lease.
     * @param string $hostName The host name of the lease.
     * @param string $comments The comments of the lease.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function addReservedLease(string $name, string $hardwareAddress, string $ipAddress, string $hostName = "", string $comments = ""){
        $response = $this->API->sendCall(["name" => $name, "hardwareAddress" => $hardwareAddress, "ipAddress" => $ipAddress, "hostName" => $hostName, "comments" => $comments], "dhcp/scopes/addReservedLease");
        return $response["status"] == "ok";
    }

    /**
     * `removeReservedLease()` - Removes a reserved lease from the DHCP scope.
     * @param string $name The name of the lease.
     * @param string $hardwareAddress The hardware address of the lease.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function removeReservedLease(string $name, string $hardwareAddress){
        $response = $this->API->sendCall(["name" => $name, "hardwareAddress" => $hardwareAddress], "dhcp/scopes/removeReservedLease");
        return $response["status"] == "ok";
    }

    /**
     * `enable()` - Enables the DHCP scope.
     * @param string $name The name of the scope.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function enable(string $name){
        $response = $this->API->sendCall(["name" => $name], "dhcp/scopes/enable");
        return $response["status"] == "ok";
    }

    public function disable(string $name){
        $response = $this->API->sendCall(["name" => $name], "dhcp/scopes/disable");
        return $response["status"] == "ok";
    }

    public function delete(string $name){
        $response = $this->API->sendCall(["name" => $name], "dhcp/scopes/delete");
        return $response["status"] == "ok";
    }
    
}
