<?php
namespace Technitium\DNSServer\API;
use Technitium\DNSServer\API\zones\catalogs;
use Technitium\DNSServer\API\zones\dnssec;
use Technitium\DNSServer\API\zones\permissions;
use Technitium\DNSServer\API\zones\records;
use Technitium\DNSServer\API\zones\options;

class zones extends API {
    public $API;

    private $catalogs;

    private $dnssec;

    private $permissions;

    private $records;

    private $options;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
        $this->eloader();
    }

    private function eloader(){
        require_once __DIR__ . "/Catalogs.zones.php";
        require_once __DIR__ . "/DNSSEC.zones.php";
        require_once __DIR__ . "/Permissions.zones.php";
        require_once __DIR__ . "/Records.zones.php";
        require_once __DIR__ . "/Options.zones.php";
    }

    public function catalogs(): catalogs {
        if(!$this->catalogs) $this->catalogs = new catalogs($this->API);
        return $this->catalogs;
    }

    public function options(): options {
        if(!$this->options) $this->options = new options($this->API);
        return $this->options;
    }

    public function dnssec(): dnssec {
        if(!$this->dnssec) $this->dnssec = new dnssec($this->API);
        return $this->dnssec;
    }

    public function permissions(): permissions {
        if(!$this->permissions) $this->permissions = new permissions($this->API);
        return $this->permissions;
    }

    public function records(): records {
        if(!$this->records) $this->records = new records($this->API);
        return $this->records;
    }

    /**
     * `clone()` - Clone a zone.
     * @param string $zone The name of the zone to clone.
     * @param string $sourceZone The name of the source zone to clone.
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function clone(string $zone, string $sourceZone){
        $response = $this->sendCall(["zone" => $zone, "sourceZone" => $sourceZone], "zones/clone");
        return $response["status"] == "ok";
    }

    /**
     * `convert()` - Convert a zone.
     * @param string $zone The name of the zone to convert.
     * @param string $type The type of zone to convert to. Valid values are [`Primary`, `Secondary`, `Stub`, `Forwarder`, `SecondaryForwarder`, `Catalog`, `SecondaryCatalog`].
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function convert(string $zone, string $type = "Primary"){
        return $this->sendCall(["zone" => $zone, "type" => $type], "zones/convert");
    }

    /**
     * `create()` - Create a new zone.
     * @param mixed $data Data to be sent. Options:
     * - `zone`: The domain name for creating the new zone. The value can be valid domain name, an IP address, or an network address in CIDR format. When value is IP address or network address, a reverse zone is created.
     * - `type`: The type of zone to be created. Valid values are [`Primary`, `Secondary`, `Stub`, `Forwarder`, `SecondaryForwarder`, `Catalog`, `SecondaryCatalog`].
     * - `catalog` (optional): The name of the catalog zone to become its member zone. This option is valid only for `Primary`, `Stub`, and `Forwarder` zones.
     * - `useSoaSerialDateScheme` (optional): Set value to `true` to enable using date scheme for SOA serial. This optional parameter is used only with `Primary` zone. Default value is `false`.
     * - `primaryNameServerAddresses` (optional): List of comma separated IP addresses or domain names of the primary name server. This optional parameter is used only with `Secondary`, `SecondaryForwarder`, `SecondaryCatalog`, and `Stub` zones. If this parameter is not used, the DNS server will try to recursively resolve the primary name server addresses automatically for `Secondary` and `Stub` zones. This option is required for `SecondaryForwarder` and `SecondaryCatalog` zones.
     * - `zoneTransferProtocol` (optional): The zone transfer protocol to be used by `Secondary`, `SecondaryForwarder`, and `SecondaryCatalog` zones. Valid values are [`Tcp`, `Tls`, `Quic`].
     * - `tsigKeyName` (optional): The TSIG key name to be used by `Secondary`, `SecondaryForwarder`, and `SecondaryCatalog` zones.
     * - `validateZone` (optional): Set value as `true` to enable ZONEMD validation. When enabled, the `Secondary` zone will be validated using the ZONEMD record after every zone transfer. The zone will get disabled if the validation fails. The zone must be DNSSEC signed for the validation to work. This option is only valid for `Secondary` zones.
     * - `protocol` (optional): The DNS transport protocol to be used by the conditional forwarder zone. This optional parameter is used with Conditional Forwarder zones. Valid values are [`Udp`, `Tcp`, `Tls`, `Https`, `Quic`]. Default `Udp` protocol is used when this parameter is missing.
     * - `forwarder` (optional): The address of the DNS server to be used as a forwarder. This optional parameter is required to be used with Conditional Forwarder zones. A special value `this-server` can be used as a forwarder which when used will forward all the requests internally to this DNS server such that you can override the zone with records and rest of the zone gets resolved via This Server.
     * - `dnssecValidation` (optional): Set this boolean value to indicate if DNSSEC validation must be done. This optional parameter is required to be used with Conditional Forwarder zones.
     * - `proxyType` (optional): The type of proxy that must be used for conditional forwarding. This optional parameter is required to be used with Conditional Forwarder zones. Valid values are [`NoProxy`, `DefaultProxy`, `Http`, `Socks5`]. Default value `DefaultProxy` is used when this parameter is missing.
     * - `proxyAddress` (optional): The proxy server address to use when `proxyType` is configured. This optional parameter is required to be used with Conditional Forwarder zones.
     * - `proxyPort` (optional): The proxy server port to use when `proxyType` is configured. This optional parameter is required to be used with Conditional Forwarder zones.
     * - `proxyUsername` (optional): The proxy server username to use when `proxyType` is configured. This optional parameter is required to be used with Conditional Forwarder zones.
     * - `proxyPassword` (optional): The proxy server password to use when `proxyType` is configured. This optional parameter is required to be used with Conditional Forwarder zones.
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function create($data){
        $response = $this->sendCall($data, "zones/create");
        return $response["status"] == "ok";
    }

    /**
     * `delete()` - Delete a zone.
     * @param string $zone The name of the zone to delete.
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function delete(string $zone){
        $response = $this->sendCall(["zone" => $zone], "zones/delete");
        return $response["status"] == "ok";
    }

    /**
     * `disable()` - Disable a zone.
     * @param string $zone The name of the zone to disable.
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function enable(string $zone){
        $response = $this->sendCall(["zone" => $zone], "zones/enable");
        return $response["status"] == "ok";
    }

    /**
     * `export()` - Export a zone.
     * @param string $zone The name of the zone to export.
     * @return srring|bool Returns the file path of the exported zone or `false` otherwise.
     */
    public function export(string $zone): string|bool{
        $result = $this->API->downloadFile("zones/export", false, ["zone" => $zone]);

        if(!empty($result)){
            return $result;
        } else {
            return false;
        }
    }

    /**
     * `import()` - Import a zone.
     * @param string $zone The name of the zone to import.
     * @param bool $overwrite Set to `true` to overwrite the existing zone.
     * @param bool $overwriteSoaSerial Set to `true` to overwrite the SOA serial number.
     * @return bool Returns `true` if successful, `false` otherwise.
     * 
     * Currently not implemented fully.
     */
    public function import(string $zone, bool $overwrite = false, bool $overwriteSoaSerial = false){
        $response = $this->sendCall(["zone" => $zone, "overwrite" => $overwrite, "overwriteSoaSerial" => $overwriteSoaSerial], "zones/import");
        return $response["status"] == "ok";
    }

    public function list(int $pageNumber = 0, int $zonesPerPage = 10){
        $response = $this->sendCall(["pageNumber" => $pageNumber, "zonesPerPage" => $zonesPerPage], "zones/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `resync()` - Resync a zone.
     * @param string $zone The name of the zone to resync.
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function resync(string $zone){
        $response = $this->sendCall(["zone" => $zone], "zones/resync");
        return $response["status"] == "ok";
    }
}
