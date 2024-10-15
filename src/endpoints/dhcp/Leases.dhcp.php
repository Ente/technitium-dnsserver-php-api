<?php
namespace Technitium\DNSServer\API\dhcp;

class leases {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `list()` - Returns the list of DHCP leases.
     * @return array|bool Returns the list of DHCP leases or `false` if the request failed.
     */
    public function list(){
        $response = $this->API->sendCall([], "dhcp/leases/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `get()` - Returns the DHCP lease details.
     * @param string $name The name of the lease.
     * @param string $clientIdentifier The client identifier of the lease. Either this or `$hardwareAddress` is required.
     * @param string $hardwareAddress The hardware address of the lease.
     * @return array|bool Returns the DHCP lease details or `false` if the request failed.
     */
    public function remove(string $name, string $clientIdentifier = "", string $hardwareAddress = ""){
        $response = $this->API->sendCall(["name" => $name, "clientIdentifier" => $clientIdentifier, "hardwareAddress" => $hardwareAddress], "dhcp/leases/remove");
        return $response["status"] == "ok";
    }
    
    /**
     * `convertToReserved()` - Converts a DHCP lease to a reserved lease.
     * @param string $name The name of the lease.
     * @param string $clientIdentifier The client identifier of the lease. Either this or `$hardwareAddress` is required.
     * @param string $hardwareAddress The hardware address of the lease.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function convertToReserved(string $name, string $clientIdentifier = "", string $hardwareAddress = ""){
        $response = $this->API->sendCall(["name" => $name, "clientIdentifier" => $clientIdentifier, "hardwareAddress" => $hardwareAddress], "dhcp/leases/convertToReserved");
        return $response["status"] == "ok";
    }

    /**
     * `convertToDynamic()` - Converts a DHCP lease to a dynamic lease.
     * @param string $name The name of the lease.
     * @param string $clientIdentifier The client identifier of the lease. Either this or `$hardwareAddress` is required.
     * @param string $hardwareAddress The hardware address of the lease.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function convertToDynamic(string $name, string $clientIdentifier = "", string $hardwareAddress = ""){
        $response = $this->API->sendCall(["name" => $name, "clientIdentifier" => $clientIdentifier, "hardwareAddress" => $hardwareAddress], "dhcp/leases/convertToDynamic");
        return $response["status"] == "ok";
    }
}
