<?php
namespace Technitium\DNSServer\API\zones\dnssec;

class properties {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `convertToNSEC()` - Convert a primary zone from NSEC3 to NSEC.
     * @param string $zone Zone name.
     * @return bool Returns `true` if conversion was successful.
     */
    public function convertToNSEC(string $zone){
        $response = $this->API->sendCall(["zone" => $zone], "zones/dnssec/properties/convertToNSEC");
        return $response["status"] == "ok";
    }

    /**
     * `convertToNSEC3()` - Convert a primary zone from NSEC to NSEC3.
     * @param string $zone Zone name.
     * @return bool Returns `true` if conversion was successful.
     */
    public function convertToNSEC3(string $zone){
        $response = $this->API->sendCall(["zone" => $zone], "zones/dnssec/properties/convertToNSEC3");
        return $response["status"] == "ok";
    }

    /**
     * `generatePrivateKey()` - Generate the private key of a zone.
     * @param mixed $data Data to set. Options:
     * - `zone`: The name of the primary zone.
     * - `keyType`: The type of key for which the private key is to be generated. Valid values are [`KeySigningKey`, `ZoneSigningKey`].
     * - `rolloverDays` (optional): The frequency in days that the DNS server must automatically rollover the private key in the zone. Valid range is 0-365 days where 0 disables rollover. Default value is 90 days for Zone Signing Key (ZSK) and 0 days for Key Signing Key (KSK).
     * - `algorithm`: The algorithm to be used for signing. Valid values are [`RSA`, `ECDSA`].
     * - `hashAlgorithm` (optional): The hash algorithm to be used when using `RSA` algorithm. Valid values are [`MD5`, `SHA1`, `SHA256`, `SHA512`]. This optional parameter is required when using `RSA` algorithm.
     * - `keySize` (optional): The size of the generated private key in bits to be used when using `RSA` algorithm. This optional parameter is required when using `RSA` algorithm.
     * - `curve` (optional): The name of the curve to be used when using `ECDSA` algorithm. Valid values are [`P256`, `P384`]. This optional parameter is required when using `ECDSA` algorithm.
     * @return bool Returns `true` if the private key was generated successfully.
     */
    public function generatePrivateKey($data){
        $response = $this->API->sendCall($data, "zones/dnssec/properties/generatePrivateKey");
        return $response["status"] == "ok";
    }

    /**
     * `get()` - Get DNSSEC properties of a zone
     * @param string $zone Zone name.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function get(string $zone){
        $response = $this->API->sendCall(["zone" => $zone], "zones/dnssec/properties/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `retireDnsKey()` - Retire DNSSEC key of a zone.
     * @param string $zone Zone name.
     * @param string $keyTag The key tag of the DNSKEY to retire.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function retireDnsKey(string $zone, string $keyTag){
        $response = $this->API->sendCall(["zone" => $zone, "keyTag" => $keyTag], "zones/dnssec/properties/retireDnsKey");
        return $response["status"] == "ok";
    }

    /**
     * `rolloverDnsKey()` - Rollover DNSSEC key of a zone.
     * @param string $zone Zone name.
     * @param string $keyTag The key tag of the DNSKEY to rollover.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function rolloverDnsKey(string $zone, string $keyTag){
        $response = $this->API->sendCall(["zone" => $zone, "keyTag" => $keyTag], "zones/dnssec/properties/rolloverDnsKey");
        return $response["status"] == "ok";
    }

    public function updateDnsKey($data){
        return $this->API->sendCall($data, "zones/dnssec/properties/updateDnsKey");
    }

    /**
     * `updateDnsKeyTtl()` - Update the TTL value of DNSKEY records in a zone.
     * @param string $zone Zone name.
     * @param int $ttl The TTL value to be used for DNSKEY records. Default value is `86400` when not specified.
     * @return bool Returns `true` if the DNSKEY TTL value was updated successfully.
     */
    public function updateDnsKeyTtl(string $zone, int $ttl = 86400){
        $response = $this->API->sendCall(["zone" => $zone, "ttl" => $ttl], "zones/dnssec/properties/updateDnsKeyTtl");
        return $response["status"] == "ok";
    }

    /**
     * `updateNSEC3Params()` - Update NSEC3 parameters of a zone.
     * @param string $zone Zone name.
     * @param int $iterations The number of iterations to use for hashing in NSEC3. Default value is `0` when not specified.
     * @param int $saltLength The length of salt in bytes to use for hashing in NSEC3. Default value is `0` when not specified.
     * @return bool Returns `true` if the NSEC3 parameters were updated successfully.
     */
    public function updateNSEC3Params(string $zone, int $iterations = 0, int $saltLength = 0){
        $response = $this->API->sendCall(["zone" => $zone, "iterations" => $iterations, "saltLength" => $saltLength], "zones/dnssec/properties/updateNSEC3Params");
        return $response["status"] == "ok";
    }


    /**
     * `updatePrivateKey()` - Update the private key of a zone.
     * @param string $zone Zone name.
     * @param string $keyTag The key tag of the private key to update.
     * @param int $rolloverDays The frequency in days that the DNS server must automatically rollover the private key in the zone. Valid range is 0-365 days where 0 disables rollover. Default value is 90 days for Zone Signing Key (ZSK) and 0 days for Key Signing Key (KSK).
     * @return bool Returns `true` if the private key was updated successfully.
     */
    public function updatePrivateKey(string $zone, string $keyTag, int $rolloverDays = 0){
        $response = $this->API->sendCall(["zone" => $zone, "keyTag" => $keyTag, "rolloverDays" => $rolloverDays], "zones/dnssec/properties/updatePrivateKey");
        return $response["status"] == "ok";
    }

    /**
     * `publishAllPrivateKeys()` - Publish all private keys of a zone.
     * @param string $zone Zone name.
     * @return bool Returns `true` if all private keys were published successfully.
     */
    public function publishAllPrivateKeys(string $zone){
        $response = $this->API->sendCall(["zone" => $zone], "zones/dnssec/properties/publishAllPrivateKeys");
        return $response["status"] == "ok";
    }

    /**
     * `deletePrivateKey()` - Delete the private key of a zone.
     * @param string $zone Zone name.
     * @param string $keyTag The key tag of the private key to delete.
     * @return bool Returns `true` if the private key was deleted successfully.
     */
    public function deletePrivateKey(string $zone, string $keyTag){
        $response = $this->API->sendCall(["zone" => $zone, "keyTag" => $keyTag], "zones/dnssec/properties/deletePrivateKey");
        return $response["status"] == "ok";
    }
}
