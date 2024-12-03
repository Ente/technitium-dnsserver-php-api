<?php
namespace Technitium\DNSServer\API\zones;
use Technitium\DNSServer\API\zones\dnssec\properties;

class dnssec {
    public $API;

    private $properties;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
        $this->eloader();
    }

    private function eloader(): void{
        require_once __DIR__ . "/Properties.dnssec.zones.php";
    }

    /**
     * `sign()` - Sign the zone.
     * @param array $data Data to be sent. Options:
     * - `zone`: The name of the primary zone to sign.
     * - `algorithm`: The algorithm to be used for signing. Valid values are [`RSA`, `ECDSA`].
     * - `hashAlgorithm` (optional): The hash algorithm to be used when using `RSA` algorithm. Valid values are [`MD5`, `SHA1`, `SHA256`, `SHA512`]. This optional parameter is required when using `RSA` algorithm.
     * - `kskKeySize` (optional): The size of the Key Signing Key (KSK) in bits to be used when using `RSA` algorithm. This optional parameter is required when using `RSA` algorithm.
     * - `zskKeySize` (optional): The size of the Zone Signing Key (ZSK) in bits to be used when using `RSA` algorithm. This optional parameter is required when using `RSA` algorithm.
     * - `curve` (optional): The name of the curve to be used when using `ECDSA` algorithm. Valid values are [`P256`, `P384`]. This optional parameter is required when using `ECDSA` algorithm.
     * - `dnsKeyTtl` (optional): The TTL value to be used for DNSKEY records. Default value is `86400` when not specified.
     * - `zskRolloverDays` (optional): The frequency in days that the DNS server must automatically rollover the Zone Signing Keys (ZSK) in the zone. Valid range is 0-365 days where 0 disables rollover. Default value is `30` when not specified.
     * - `nxProof` (optional): The type of proof of non-existence that must be used for signing the zone. Valid values are [`NSEC`, `NSEC3`]. Default value is `NSEC` when not specified.
     * - `iterations` (optional): The number of iterations to use for hashing in NSEC3. This optional parameter is only applicable when using `NSEC3` as the `nxProof`. Default value is `0` when not specified.
     * - `saltLength` (optional): The length of salt in bytes to use for hashing in NSEC3. This optional parameter is only applicable when using `NSEC3` as the `nxProof`. Default value is `0` when not specified.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function sign($data): bool{
        $response = $this->API->sendCall($data, "zones/dnssec/sign");
        return $response["status"] == "ok";
    }

    /**
     * `unsign()` - Unsign the zone.
     * @param string $zone The name of the zone to unsign.
     * @return bool Returns `true` if the zone was unsigned successfully, `false` otherwise.
     */
    public function unsign(string $zone): bool{
        $response = $this->API->sendCall(["zone" => $zone], "zones/dnssec/unsign");
        return $response["status"] == "ok";
    }

    /**
     * `viewDS()` - View the DS records of the zone.
     * @param string $zone The name of the zone to view DS records.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function viewDS(string $zone): array|bool{
        return $this->API->sendCall(["zone" => $zone], "zones/dnssec/viewDS");
    }

    public function properties(): properties {
        if(!$this->properties) $this->properties = new properties($this->API);
        return $this->properties;
    }
}
