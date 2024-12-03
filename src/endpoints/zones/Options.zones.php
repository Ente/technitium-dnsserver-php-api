<?php
namespace Technitium\DNSServer\API\zones;

class options {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `get()` - Get zone options.
     * @param string $zone Zone name.
     * @param bool $includeAvailableCatalogZoneNames Include available catalog zone names.
     * @param bool $includeAvailableTsigKeyNames Include available TSIG key names.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function get(string $zone, bool $includeAvailableCatalogZoneNames = true, bool $includeAvailableTsigKeyNames = false): array|bool{
        if($includeAvailableCatalogZoneNames){
            $includeAvailableCatalogZoneNames = "true";
        } else {
            $includeAvailableCatalogZoneNames = "false";
        }
        if($includeAvailableTsigKeyNames){
            $includeAvailableTsigKeyNames = "true";
        } else {
            $includeAvailableTsigKeyNames = "false";
        }
        $response = $this->API->sendCall([
            "zone" => $zone,
            "includeAvailableCatalogZoneNames" => $includeAvailableCatalogZoneNames,
            "includeAvailableTsigKeyNames" => $includeAvailableTsigKeyNames
        ], "zones/options/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `set()` - Set zone options.
     * @param array $data Data to set. Options:
     * - `zone`: The domain name of the zone to set options.
     * - `disabled` (optional): Sets if the zone is enabled or disabled.
     * - `catalog` (optional): Set a Catalog zone name to register as its member zone. This option is valid only for `Primary`, `Stub`, and `Forwarder` zones.
     * - `overrideCatalogQueryAccess` (optional): Set to `true` to override Query Access option in the Catalog zone. This option is valid only for `Primary`, `Stub`, and `Forwarder` zones.
     * - `overrideCatalogZoneTransfer` (optional): Set to `true` to override Zone Transfer option in the Catalog zone. This option is valid only for `Primary`, and `Forwarder` zones.
     * - `overrideCatalogNotify` (optional): Set to `true` to override Notify option in the Catalog zone.  This option is valid only for `Primary`, and `Forwarder` zones.
     * - `primaryNameServerAddresses` (optional): List of comma separated IP addresses or domain names of the primary name server. This optional parameter is used only with `Secondary`, `SecondaryForwarder`, `SecondaryCatalog`, and `Stub` zones. If this parameter is not used, the DNS server will try to recursively resolve the primary name server addresses automatically for `Secondary` and `Stub` zones. This option is required for `SecondaryForwarder` and `SecondaryCatalog` zones.
     * - `primaryZoneTransferProtocol `(optional): The zone transfer protocol to be used by `Secondary`, `SecondaryForwarder`, and `SecondaryCatalog` zones. Valid values are [`Tcp`, `Tls`, `Quic`].
     * - `primaryZoneTransferTsigKeyName` (optional): The TSIG key name to be used by `Secondary`, `SecondaryForwarder`, and `SecondaryCatalog` zones for zone transfer.
     * - `validateZone`: (optional): Set value as `true` to enable ZONEMD validation. When enabled, the `Secondary` zone will be validated using the ZONEMD record after every zone transfer. The zone will get disabled if the validation fails. The zone must be DNSSEC signed for the validation to work. This option is only valid for `Secondary` zones.
     * - `queryAccess` (optional): Valid options are [`Deny`, `Allow`, `AllowOnlyPrivateNetworks`, `AllowOnlyZoneNameServers`, `UseSpecifiedNetworkACL`, `AllowZoneNameServersAndUseSpecifiedNetworkACL`].
     * - `queryAccessNetworkACL` (optional): A comma separated Access Control List (ACL) of Network Access Control (NAC) entry. NAC is an IP address or network address to allow. Add `!` at the start of the NAC to deny access. The ACL is processed in the same order its listed. If no networks match, the default policy is to deny all except loopback. Set this parameter to `false` to remove existing values. This option is valid for all zones except `SecondaryCatalog` zone and only when `queryAccess` is set to `UseSpecifiedNetworkACL` or `AllowZoneNameServersAndUseSpecifiedNetworkACL`.
     * - `zoneTransfer` (optional): Sets if the zone allows zone transfer. Valid options are [`Deny`, `Allow`, `AllowOnlyZoneNameServers`, `UseSpecifiedNetworkACL`, `AllowZoneNameServersAndUseSpecifiedNetworkACL`]. This option is valid only for Primary and Secondary zones.
     * - `zoneTransferNetworkACL` (optional): A comma separated Access Control List (ACL) of Network Access Control (NAC) entry. NAC is an IP address or network address to allow. Add `!` at the start of the NAC to deny access. The ACL is processed in the same order its listed. If no networks match, the default policy is to deny all. Set this parameter to `false` to remove existing values. This option is valid only for `Primary`, `Secondary`, `Forwarder`, and `Catalog` zones and only when `zoneTransfer` is set to `UseSpecifiedNetworkACL` or `AllowZoneNameServersAndUseSpecifiedNetworkACL`.
     * - `zoneTransferTsigKeyNames` (optional): A list of comma separated TSIG keys names that are authorized to perform a zone transfer. Set this option to `false` to clear all key names. This option is valid only for `Primary`, `Secondary`, `Forwarder`, and `Catalog` zones.
     * - `notify` (optional): Sets if the DNS server should notify other DNS servers for zone updates. Valid options for `Primary` and `Secondary` zones are [`None`, `ZoneNameServers`, `SpecifiedNameServers`, `BothZoneAndSpecifiedNameServers`]. Valid options for `Forwarder` and `Catalog` zones are [`None`, `SpecifiedNameServers`]. This option is valid only for `Primary`, `Secondary`, `Forwarder`, and `Catalog` zones.
     * - `notifyNameServers` (optional): A list of comma separated IP addresses which should be notified by the DNS server for zone updates. This list is used only when `notify` option is set to `SpecifiedNameServers` or `BothZoneAndSpecifiedNameServers`. This option is valid only for `Primary`, `Secondary`, `Forwarder`, and `Catalog` zones.
     * - `update` (optional): Sets if the DNS server should allow dynamic updates (RFC 2136). This option is valid only for `Primary`, `Secondary`, and `Forwarder` zones. Valid options for `Primary` zones are [`Deny`, `Allow`, `AllowOnlyZoneNameServers`, `UseSpecifiedNetworkACL`, `AllowZoneNameServersAndUseSpecifiedNetworkACL`]. Valid options for `Secondary` and `Forwarder` zones are [`Deny`, `Allow`, `UseSpecifiedNetworkACL`].
     * - `updateNetworkACL` (optional): A comma separated Access Control List (ACL) of Network Access Control (NAC) entry. NAC is an IP address or network address to allow. Add `!` at the start of the NAC to deny access. The ACL is processed in the same order its listed. If no networks match, the default policy is to deny all. Set this parameter to `false` to remove existing values. This option is valid only for `Primary`, `Secondary`, and `Forwarder` zones and only when `update` is set to `UseSpecifiedNetworkACL` or `AllowZoneNameServersAndUseSpecifiedNetworkACL`.
     * - `updateSecurityPolicies` (optional): A pipe `|` separated table data of security policies with each row containing the TSIG keys name, domain name, and comma separated record types that are allowed. Use wildcard domain name to specify all sub domain names. Set this option to `false` to clear all security policies and stop TSIG authentication. This option is valid only for `Primary` and `Forwarder` zones.
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function set(array $data): bool{
        $response = $this->API->sendCall($data, "zones/options/set");
        return $response["status"] == "ok";
    }
}
