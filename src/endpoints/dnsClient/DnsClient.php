<?php
namespace Technitium\DNSServer\API;

class dnsClient extends API {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `resolve()` - Resolves a domain name to an IP address.
     * @param string $data Data to set for the query. Options:
     *  - `server`: The name server to query using the DNS client. Use `recursive-resolver` to perform recursive resolution. Use `system-dns` to query the DNS servers configured on the system.
     * - `domain`: The domain name to query.
     * - `type`: The type of the query.
     * - `protocol` (optional): The DNS transport protocol to be used to query. Valid values are [`Udp`, `Tcp`, `Tls`, `Https`, `Quic`]. The default value of `Udp` is used when the parameter is missing.
     * - `dnssec` (optional): Set to `true` to enable DNSSEC validation.
     * - `eDnsClientSubnet` (optional): The network address to be used with EDNS Client Subnet option in the request.
     * - `import` (optional): This parameter when set to `true` indicates that the response of the DNS query should be imported in the an authoritative zone on this DNS server. Default value is `false` when this parameter is missing. If a zone does not exists, a primary zone for the `domain` name is created and the records from the response are set into the zone. Import can be done only for primary and forwarder type of zones. When `type` is set to AXFR, then the import feature will work as if a zone transfer was requested and the complete zone will be updated as per the zone transfer response. Note that any existing record type for the given `type` will be overwritten when syncing the records. It is recommended to use `recursive-resolver` or the actual name server address for the `server` parameter when importing records. You must have Zones Modify permission to create a zone or Zone Modify permission to import records into an existing zone.
     * @return array|bool Returns the result array or `false` otherwise.
     */
    public function resolve($data){
        $response = $this->API->sendCall($data, "dnsClient/resolve");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }
}
