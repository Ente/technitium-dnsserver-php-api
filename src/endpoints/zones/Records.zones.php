<?php
namespace Technitium\DNSServer\API\zones;

class records {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `add()` - Add a record to a zone.
     * @param array $data Data to set. Options:
     * - `domain`: The domain name of the zone to add record.
     * - `zone` (optional): The name of the authoritative zone into which the `domain` exists. When unspecified, the closest authoritative zone will be used.
     * - `type`: The DNS resource record type. Supported record types are [`A`, `AAAA`, `NS`, `CNAME`, `PTR`, `MX`, `TXT`, `SRV`, `DNAME`, `DS`, `SSHFP`, `TLSA`, `SVCB`, `HTTPS`, `URI`, `CAA`] and proprietary types [`ANAME`, `FWD`, `APP`]. Unknown record types are also supported since v11.2.
     * - `ttl` (optional): The DNS resource record TTL value. This is the value in seconds that the DNS resolvers can cache the record for. When not specified the default TTL value from settings will be used.
     * - `overwrite` (optional): This option when set to `true` will overwrite existing resource record set for the selected `type` with the new record. Default value of `false` will add the new record into existing resource record set.
     * - `comments` (optional): Sets comments for the added resource record.
     * - `expiryTtl` (optional): Set to automatically delete the record when the value in seconds elapses since the record’s last modified time.
     * - `ipAddress` (optional): The IP address for adding `A` or `AAAA` record. A special value of `request-ip-address` can be used to set the record with the IP address of the API HTTP request to help with dynamic DNS update applications. This option is required and used only for `A` and `AAAA` records.
     * - `ptr` (optional): Set this option to `true` to add a reverse PTR record for the IP address in the `A` or `AAAA` record. This option is used only for `A` and `AAAA` records.
     * - `createPtrZone` (optional): Set this option to `true` to create a reverse zone for PTR record. This option is used for `A` and `AAAA` records.
     * - `updateSvcbHints` (optional): Set this option to `true` to update any SVCB/HTTPS records in the zone that has Automatic Hints option enabled and matches its target name with the current record's domain name. This option is used for `A` and `AAAA` records.
     * - `nameServer` (optional): The name server domain name. This option is required for adding `NS` record.
     * - `glue` (optional): This is the glue address for the name server in the `NS` record. This optional parameter is used for adding `NS` record.
     * - `cname` (optional): The CNAME domain name. This option is required for adding `CNAME` record.
     * - `ptrName` (optional): The PTR domain name. This option is required for adding `PTR` record.
     * - `exchange` (optional): The exchange domain name. This option is required for adding `MX` record.
     * - `preference` (optional): This is the preference value for `MX` record type. This option is required for adding `MX` record.
     * - `text` (optional): The text data for `TXT` record. This option is required for adding `TXT` record.
     * - `splitText` (optional): Set to `true` for using new line char to split text into multiple character-strings for adding `TXT` record.
     * - `mailbox` (optional): Set an email address for adding `RP` record.
     * - `txtDomain` (optional): Set a `TXT` record's domain name for adding `RP` record.
     * - `priority` (optional): This parameter is required for adding the `SRV` record.
     * - `weight` (optional): This parameter is required for adding the `SRV` record.
     * - `port` (optional): This parameter is required for adding the `SRV` record.
     * - `target` (optional): This parameter is required for adding the `SRV` record.
     * - `naptrOrder` (optional): This parameter is required for adding the `NAPTR` record.
     * - `naptrPreference` (optional): This parameter is required for adding the `NAPTR` record.
     * - `naptrFlags` (optional): This parameter is required for adding the `NAPTR` record.
     * - `naptrServices` (optional): This parameter is required for adding the `NAPTR` record.
     * - `naptrRegexp` (optional): This parameter is required for adding the `NAPTR` record.
     * - `naptrReplacement` (optional): This parameter is required for adding the `NAPTR` record.
     * - `dname` (optional): The DNAME domain name. This option is required for adding `DNAME` record.
     * - `keyTag` (optional): This parameter is required for adding `DS` record.
     * - `algorithm` (optional): Valid values are [`RSAMD5`, `DSA`, `RSASHA1`, `DSA-NSEC3-SHA1`, `RSASHA1-NSEC3-SHA1`, `RSASHA256`, `RSASHA512`, `ECC-GOST`, `ECDSAP256SHA256`, `ECDSAP384SHA384`, `ED25519`, `ED448`]. This parameter is required for adding `DS` record.
     * - `digestType` (optional): Valid values are [`SHA1`, `SHA256`, `GOST-R-34-11-94`, `SHA384`]. This parameter is required for adding `DS` record.
     * - `digest` (optional): A hex string value. This parameter is required for adding `DS` record.
     * - `sshfpAlgorithm` (optional): Valid values are [`RSA`, `DSA`, `ECDSA`, `Ed25519`, `Ed448`]. This parameter is required for adding `SSHFP` record.
     * - `sshfpFingerprintType` (optional): Valid values are [`SHA1`, `SHA256`]. This parameter is required for adding `SSHFP` record.
     * - `sshfpFingerprint` (optional): A hex string value. This parameter is required for adding `SSHFP` record.
     * - `tlsaCertificateUsage` (optional): Valid values are [`PKIX-TA`, `PKIX-EE`, `DANE-TA`, `DANE-EE`]. This parameter is required for adding `TLSA` record.
     * - `tlsaSelector` (optional): Valid values are [`Cert`, `SPKI`]. This parameter is required for adding `TLSA` record.
     * - `tlsaMatchingType` (optional): Valid value are [`Full`, `SHA2-256`, `SHA2-512`]. This parameter is required for adding `TLSA` record.
     * - `tlsaCertificateAssociationData` (optional): A X509 certificate in PEM format or a hex string value. This parameter is required for adding `TLSA` record.
     * - `svcPriority` (optional): The priority value for `SVCB` or `HTTPS` record. This parameter is required for adding `SCVB` or `HTTPS` record.
     * - `svcTargetName` (optional): The target domain name for `SVCB` or `HTTPS` record. This parameter is required for adding `SCVB` or `HTTPS` record.
     * - `svcParams` (optional): The service parameters for `SVCB` or `HTTPS` record which is a pipe separated list of key and value. For example, `alpn|h2,h3|port|53443`. To clear existing values, set it to `false`. This parameter is required for adding `SCVB` or `HTTPS` record.
     * - `autoIpv4Hint` (optional): Set this option to `true` to enable Automatic Hints for the `ipv4hint` parameter in the `svcParams`. This option is valid only for `SVCB` and `HTTPS` records.
     * - `autoIpv6Hint` (optional): Set this option to `true` to enable Automatic Hints for the `ipv6hint` parameter in the `svcParams`. This option is valid only for `SVCB` and `HTTPS` records.
     * - `uriPriority` (optional): The priority value for adding the `URI` record.
     * - `uriWeight` (optional): The weight value for adding the `URI` record.
     * - `uri` (optional): The URI value for adding the `URI` record.
     * - `flags` (optional): This parameter is required for adding the `CAA` record.
     * - `tag` (optional): This parameter is required for adding the `CAA` record.
     * - `value` (optional): This parameter is required for adding the `CAA` record.
     * - `aname` (optional): The ANAME domain name. This option is required for adding `ANAME` record.
     * - `protocol` (optional): This parameter is required for adding the `FWD` record. Valid values are [`Udp`, `Tcp`, `Tls`, `Https`, `Quic`].
     * - `forwarder` (optional): The forwarder address. A special value of `this-server` can be used to directly forward requests internally to the DNS server. This parameter is required for adding the `FWD` record.
     * - `forwarderPriority` (optional): Set an integer priority value for adding the `FWD` record. Forwarders with high priority (lower value) will be queried before trying for low priority forwarders. Forwarders with the same priority will be concurrently queried.
     * - `dnssecValidation` (optional): Set this boolean value to indicate if DNSSEC validation must be done. This optional parameter is to be used with FWD records. Default value is `false`.
     * - `proxyType` (optional): The type of proxy that must be used for conditional forwarding. This optional parameter is to be used with FWD records. Valid values are [`NoProxy`, `DefaultProxy`, `Http`, `Socks5`]. Default value `DefaultProxy` is used when this parameter is missing.
     * - `proxyAddress` (optional): The proxy server address to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `proxyPort` (optional): The proxy server port to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `proxyUsername` (optional): The proxy server username to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `proxyPassword` (optional): The proxy server password to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `appName` (optional): The name of the DNS app. This parameter is required for adding the `APP` record.
     * - `classPath` (optional): This parameter is required for adding the `APP` record.
     * - `recordData` (optional): This parameter is used for adding the `APP` record as per the DNS app requirements.
     * - `rdata` (optional): This parameter is used for adding unknown i.e. unsupported record types. The value must be formatted as a hex string or a colon separated hex string.
     * @return bool Returns `true` if the record was added successfully.
     */
    public function add(array $data){
        $response = $this->API->sendCall($data, "zones/records/add");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `delete()` - Delete a record from a zone.
     * @param array $data Data to set. Options:
     * - `domain`: The domain name of the zone to delete the record.
     * - `zone` (optional): The name of the authoritative zone into which the `domain` exists. When unspecified, the closest authoritative zone will be used.
     * - `type`: The type of the resource record to delete.
     * - `ipAddress` (optional): This parameter is required when deleting `A` or `AAAA` record.
     * - `updateSvcbHints` (optional): Set this option to `true` to update any SVCB/HTTPS records in the zone that has Automatic Hints option enabled and matches its target name with the current record's domain name. This option is used for `A` and `AAAA` records.
     * - `nameServer` (optional): This parameter is required when deleting `NS` record.
     * - `ptrName` (optional): This parameter is required when deleting `PTR` record.
     * - `preference` (optional): This parameter is required when deleting `MX` record.
     * - `exchange` (optional): This parameter is required when deleting `MX` record.
     * - `text` (optional): This parameter is required when deleting `TXT` record.
     * - `splitText` (optional): This parameter is used when deleting `TXT` record. Default value is set to `false` when unspecified.
     * - `mailbox` (optional): Set an email address for deleting `RP` record.
     * - `txtDomain` (optional): Set a `TXT` record's domain name for deleting `RP` record.
     * - `priority` (optional): This parameter is required when deleting the `SRV` record.
     * - `weight` (optional): This parameter is required when deleting the `SRV` record.
     * - `port` (optional): This parameter is required when deleting the `SRV` record.
     * - `target` (optional): This parameter is required when deleting the `SRV` record.
     * - `naptrOrder` (optional): This parameter is required when deleting the `NAPTR` record.
     * - `naptrPreference` (optional): This parameter is required when deleting the `NAPTR` record.
     * - `naptrFlags` (optional): This parameter is required when deleting the `NAPTR` record.
     * - `naptrServices` (optional): This parameter is required when deleting the `NAPTR` record.
     * - `naptrRegexp` (optional): This parameter is required when deleting the `NAPTR` record.
     * - `naptrReplacement` (optional): This parameter is required when deleting the `NAPTR` record.
     * - `keyTag` (optional): This parameter is required when deleting `DS` record.
     * - `algorithm` (optional): This parameter is required when deleting `DS` record.
     * - `digestType` (optional): This parameter is required when deleting `DS` record.
     * - `digest` (optional): This parameter is required when deleting `DS` record.
     * - `sshfpAlgorithm` (optional): This parameter is required when deleting `SSHFP` record.
     * - `sshfpFingerprintType` (optional): This parameter is required when deleting `SSHFP` record.
     * - `sshfpFingerprint` (optional): This parameter is required when deleting `SSHFP` record.
     * - `tlsaCertificateUsage` (optional): This parameter is required when deleting `TLSA` record.
     * - `tlsaSelector` (optional): This parameter is required when deleting `TLSA` record.
     * - `tlsaMatchingType` (optional): This parameter is required when deleting `TLSA` record.
     * - `tlsaCertificateAssociationData` (optional): This parameter is required when deleting `TLSA` record.
     * - `svcPriority` (optional): The priority value for `SVCB` or `HTTPS` record. This parameter is required for deleting `SCVB` or `HTTPS` record.
     * - `svcTargetName` (optional): The target domain name for `SVCB` or `HTTPS` record. This parameter is required for deleting `SCVB` or `HTTPS` record.
     * - `svcParams` (optional): The service parameters for `SVCB` or `HTTPS` record which is a pipe separated list of key and value. For example, `alpn|h2,h3|port|53443`. To clear existing values, set it to `false`. This parameter is required for deleting `SCVB` or `HTTPS` record.
     * - `uriPriority` (optional): The priority value in the `URI` record. This parameter is required when deleting the `URI` record.
     * - `uriWeight` (optional): The weight value in the `URI` record. This parameter is required when deleting the `URI` record.
     * - `uri` (optional): The URI value in the `URI` record. This parameter is required when deleting the `URI` record.
     * - `flags` (optional): This is the flags parameter in the `CAA` record. This parameter is required when deleting the `CAA` record.
     * - `tag` (optional): This is the tag parameter in the `CAA` record. This parameter is required when deleting the `CAA` record.
     * - `value` (optional): This parameter is required when deleting the `CAA` record.
     * - `aname` (optional): This parameter is required when deleting the `ANAME` record.
     * - `protocol` (optional): This is the protocol parameter in the FWD record. Valid values are [`Udp`, `Tcp`, `Tls`, `Https`, `Quic`]. This parameter is optional and default value `Udp` will be used when deleting the `FWD` record.
     * - `forwarder` (optional): This parameter is required when deleting the `FWD` record.
     * - `rdata` (optional): This parameter is used for deleting unknown i.e. unsupported record types. The value must be formatted as a hex string or a colon separated hex string.
     * @return bool
     */
    public function delete(array $data){
        $response = $this->API->sendCall($data, "zones/records/delete");
        return $response["status"] == "ok";
    }

    /**
     * `get()` - Get records of a zone.
     * @param string $domain The domain name of the zone to get records.
     * @param string $zone (optional) The name of the authoritative zone into which the `domain` exists. When unspecified, the closest authoritative zone will be used.
     * @param bool $listZone (optional) Set this option to `true` to list all records in the zone. Default value is `false`.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function get(string $domain, string $zone = "", bool $listZone = false){
        if($listZone){$listZone = "true";} else {$listZone = "false";}
        $response = $this->API->sendCall(["domain" => $domain, "zone" => $zone, "listZone" => $listZone], "zones/records/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `update()` - Update a record in a zone.
     * @param array $data Data to set. Options:
     * - `domain`: The domain name of the zone to update the record.
     * - `zone` (optional): The name of the authoritative zone into which the `domain` exists. When unspecified, the closest authoritative zone will be used.
     * - `type`: The type of the resource record to update.
     * - `newDomain` (optional): The new domain name to be set for the record. To be used to rename sub domain name of the record.
     * - `ttl` (optional): The TTL value of the resource record. Default value of `3600` is used when parameter is missing.
     * - `disable` (optional): Specifies if the record should be disabled. The default value is `false` when this parameter is missing.
     * - `comments` (optional): Sets comments for the updated resource record.
     * - `expiryTtl` (optional): Set to automatically delete the record when the value in seconds elapses since the record’s last modified time.
     * - `ipAddress` (optional): The current IP address in the `A` or `AAAA` record. This parameter is required when updating `A` or `AAAA` record.
     * - `newIpAddress` (optional): The new IP address in the `A` or `AAAA` record. This parameter when missing will use the current value in the record.
     * - `ptr` (optional): Set this option to `true` to specify if the PTR record associated with the `A` or `AAAA` record must also be updated. This option is used only for `A` and `AAAA` records.
     * - `createPtrZone` (optional): Set this option to `true` to create a reverse zone for PTR record. This option is used only for `A` and `AAAA` records.
     * - `updateSvcbHints` (optional): Set this option to `true` to update any SVCB/HTTPS records in the zone that has Automatic Hints option enabled and matches its target name with the current record's domain name. This option is used for `A` and `AAAA` records.
     * - `nameServer` (optional): The current name server domain name. This option is required for updating `NS` record.
     * - `newNameServer` (optional): The new server domain name. This option is used for updating `NS` record.
     * - `glue` (optional): The comma separated list of IP addresses set as glue for the NS record. This parameter is used only when updating `NS` record.
     * - `cname` (optional): The CNAME domain name to update in the existing `CNAME` record.
     * - `primaryNameServer` (optional): This is the primary name server parameter in the SOA record. This parameter is required when updating the SOA record.
     * - `responsiblePerson` (optional): This is the responsible person parameter in the SOA record. This parameter is required when updating the SOA record.
     * - `serial` (optional): This is the serial parameter in the SOA record. This parameter is required when updating the SOA record.
     * - `refresh` (optional): This is the refresh parameter in the SOA record. This parameter is required when updating the SOA record.
     * - `retry` (optional): This is the retry parameter in the SOA record. This parameter is required when updating the SOA record.
     * - `expire` (optional): This is the expire parameter in the SOA record. This parameter is required when updating the SOA record.
     * - `minimum` (optional): This is the minimum parameter in the SOA record. This parameter is required when updating the SOA record.
     * - `ptrName`(optional): The current PTR domain name. This option is required for updating `PTR` record.
     * - `newPtrName`(optional): The new PTR domain name. This option is required for updating `PTR` record.
     * - `preference` (optional): The current preference value in an MX record. This parameter when missing will default to `1` value. This parameter is used only when updating `MX` record.
     * - `newPreference` (optional): The new preference value in an MX record. This parameter when missing will use the old value. This parameter is used only when updating `MX` record.
     * - `exchange` (optional): The current exchange domain name. This option is required for updating `MX` record.
     * - `newExchange` (optional): The new exchange domain name. This option is required for updating `MX` record.
     * - `text` (optional): The current text value. This option is required for updating `TXT` record.
     * - `newText` (optional): The new text value. This option is required for updating `TXT` record.
     * - `splitText` (optional): The current split text value. This option is used for updating `TXT` record and is set to `false` when unspecified.
     * - `newSplitText` (optional): The new split text value. This option is used for updating `TXT` record and is set to current split text value when unspecified.
     * - `mailbox` (optional): The current email address value. This option is required for updating `RP` record.
     * - `newMailbox` (optional): The new email address value. This option is used for updating `RP` record and is set to the current value when unspecified.
     * - `txtDomain` (optional): The current TXT record's domain name value. This option is required for updating `RP` record.
     * - `newTxtDomain` (optional). The new TXT record's domain name value. This option is used for updating `RP` record and is set to the current value when unspecified.
     * - `priority` (optional): This is the current priority in the SRV record. This parameter is required when updating the `SRV` record.
     * - `newPriority` (optional): This is the new priority in the SRV record. This parameter when missing will use the old value. This parameter is used when updating the `SRV` record.
     * - `weight` (optional): This is the current weight in the SRV record. This parameter is required when updating the `SRV` record.
     * - `newWeight` (optional): This is the new weight in the SRV record. This parameter when missing will use the old value. This parameter is used when updating the `SRV` record.
     * - `port` (optional): This is the port parameter in the SRV record. This parameter is required when updating the `SRV` record.
     * - `newPort` (optional): This is the new value of the port parameter in the SRV record. This parameter when missing will use the old value. This parameter is used to update the port parameter in the `SRV` record.
     * - `target` (optional): The current target value. This parameter is required when updating the `SRV` record.
     * - `newTarget` (optional): The new target value. This parameter when missing will use the old value. This parameter is required when updating the `SRV` record.
     * - `naptrOrder` (optional): The current value in the NAPTR record. This parameter is required when updating the `NAPTR` record.
     * - `naptrNewOrder` (optional): The new value in the NAPTR record. This parameter when missing will use the old value. This parameter is used when updating the `NAPTR` record.
     * - `naptrPreference` (optional): The current value in the NAPTR record. This parameter is required when updating the `NAPTR` record.
     * - `naptrNewPreference` (optional): The new value in the NAPTR record. This parameter when missing will use the old value. This parameter is used when updating the `NAPTR` record.
     * - `naptrFlags` (optional): The current value in the NAPTR record. This parameter is required when updating the `NAPTR` record.
     * - `naptrNewFlags` (optional): The new value in the NAPTR record. This parameter when missing will use the old value. This parameter is used when updating the `NAPTR` record.
     * - `naptrServices` (optional): The current value in the NAPTR record. This parameter is required when updating the `NAPTR` record.
     * - `naptrNewServices` (optional): The new value in the NAPTR record. This parameter when missing will use the old value. This parameter is used when updating the `NAPTR` record.
     * - `naptrRegexp` (optional): The current value in the NAPTR record. This parameter is required when updating the `NAPTR` record.
     * - `naptrNewRegexp` (optional): The new value in the NAPTR record. This parameter when missing will use the old value. This parameter is used when updating the `NAPTR` record.
     * - `naptrReplacement` (optional): The current value in the NAPTR record. This parameter is required when updating the `NAPTR` record.
     * - `naptrNewReplacement` (optional): The new value in the NAPTR record. This parameter when missing will use the old value. This parameter is used when updating the `NAPTR` record.
     * - `dname` (optional): The DNAME domain name. This parameter is required when updating the `DNAME` record.
     * - `keyTag` (optional): This parameter is required when updating `DS` record.
     * - `newKeyTag` (optional): This parameter is required when updating `DS` record.
     * - `algorithm` (optional): This parameter is required when updating `DS` record.
     * - `newAlgorithm` (optional): This parameter is required when updating `DS` record.
     * - `digestType` (optional): This parameter is required when updating `DS` record.
     * - `newDigestType` (optional): This parameter is required when updating `DS` record.
     * - `digest` (optional): This parameter is required when updating `DS` record.
     * - `newDigest` (optional): This parameter is required when updating `DS` record.
     * - `sshfpAlgorithm` (optional): This parameter is required when updating `SSHFP` record.
     * - `newSshfpAlgorithm` (optional): This parameter is required when updating `SSHFP` record.
     * - `sshfpFingerprintType` (optional): This parameter is required when updating `SSHFP` record.
     * - `newSshfpFingerprintType` (optional): This parameter is required when updating `SSHFP` record.
     * - `sshfpFingerprint` (optional): This parameter is required when updating `SSHFP` record.
     * - `newSshfpFingerprint` (optional): This parameter is required when updating `SSHFP` record.
     * - `tlsaCertificateUsage` (optional): This parameter is required when updating `TLSA` record.
     * - `newTlsaCertificateUsage` (optional): This parameter is required when updating `TLSA` record.
     * - `tlsaSelector` (optional): This parameter is required when updating `TLSA` record.
     * - `newTlsaSelector` (optional): This parameter is required when updating `TLSA` record.
     * - `tlsaMatchingType` (optional): This parameter is required when updating `TLSA` record.
     * - `newTlsaMatchingType` (optional): This parameter is required when updating `TLSA` record.
     * - `tlsaCertificateAssociationData` (optional): This parameter is required when updating `TLSA` record.
     * - `newTlsaCertificateAssociationData` (optional): This parameter is required when updating `TLSA` record.
     * - `svcPriority` (optional): The priority value for `SVCB` or `HTTPS` record. This parameter is required for updating `SCVB` or `HTTPS` record.
     * - `newSvcPriority` (optional): The new priority value for `SVCB` or `HTTPS` record. This parameter when missing will use the old value.
     * - `svcTargetName` (optional): The target domain name for `SVCB` or `HTTPS` record. This parameter is required for updating `SCVB` or `HTTPS` record.
     * - `newSvcTargetName` (optional): The new target domain name for `SVCB` or `HTTPS` record. This parameter when missing will use the old value.
     * - `svcParams` (optional): The service parameters for `SVCB` or `HTTPS` record which is a pipe separated list of key and value. For example, `alpn|h2,h3|port|53443`. To clear existing values, set it to `false`. This parameter is required for updating `SCVB` or `HTTPS` record.
     * - `newSvcParams` (optional): The new service parameters for `SVCB` or `HTTPS` record which is a pipe separated list of key and value. To clear existing values, set it to `false`. This parameter when missing will use the old value.
     *  - `autoIpv4Hint` (optional): Set this option to `true` to enable Automatic Hints for the `ipv4hint` parameter in the `newSvcParams`. This option is valid only for `SVCB` and `HTTPS` records.
     * - `autoIpv6Hint` (optional): Set this option to `true` to enable Automatic Hints for the `ipv6hint` parameter in the `newSvcParams`. This option is valid only for `SVCB` and `HTTPS` records.
     * - `uriPriority` (optional): The priority value for the `URI` record. This parameter is required for updating the `URI` record.
     * - `newUriPriority` (optional): The new priority value for the `URI` record. This parameter when missing will use the old value.
     * - `uriWeight` (optional): The weight value for the `URI` record. This parameter is required for updating the `URI` record.
     * - `newUriWeight` (optional): The new weight value for the `URI` record. This parameter when missing will use the old value.
     * - `uri` (optional): The URI value for the `URI` record. This parameter is required for updating the `URI` record.
     * - `newUri` (optional): The new URI value for the `URI` record. This parameter when missing will use the old value.
     * - `flags` (optional): This is the flags parameter in the `CAA` record. This parameter is required when updating the `CAA` record.
     * - `newFlags` (optional): This is the new value of the flags parameter in the `CAA` record. This parameter is used to update the flags parameter in the `CAA` record.
     * - `tag` (optional): This is the tag parameter in the `CAA` record. This parameter is required when updating the `CAA` record.
     * - `newTag` (optional): This is the new value of the tag parameter in the `CAA` record. This parameter is used to update the tag parameter in the `CAA` record.
     * - `value` (optional): The current value in `CAA` record. This parameter is required when updating the `CAA` record.
     * - `newValue` (optional): The new value in `CAA` record. This parameter is required when updating the `CAA` record.
     * - `aname` (optional): The current `ANAME` domain name. This parameter is required when updating the `ANAME` record.
     * - `newAName` (optional): The new `ANAME` domain name. This parameter is required when updating the `ANAME` record.
     * - `protocol` (optional): This is the current protocol value in the `FWD` record. Valid values are [`Udp`, `Tcp`, `Tls`, `Https`, `Quic`]. This parameter is optional and default value `Udp` will be used when updating the `FWD` record.
     * - `newProtocol` (optional): This is the new protocol value in the `FWD` record. Valid values are [`Udp`, `Tcp`, `Tls`, `Https`, `Quic`]. This parameter is optional and default value `Udp` will be used when updating the `FWD` record.
     * - `forwarder` (optional): The current forwarder address. This parameter is required when updating the `FWD` record.
     * - `newForwarder` (optional): The new forwarder address. This parameter is required when updating the `FWD` record.
     * - `forwarderPriority` (optional): The current forwarder priority value. This optional parameter is to be used with `FWD` record. When unspecified, the default value of `0` will be used.
     * - `dnssecValidation` (optional): Set this boolean value to indicate if DNSSEC validation must be done. This optional parameter is to be used with FWD records. Default value is `false`.
     * - `proxyType` (optional): The type of proxy that must be used for conditional forwarding. This optional parameter is to be used with FWD records. Valid values are [`NoProxy`, `DefaultProxy`, `Http`, `Socks5`]. Default value `DefaultProxy` is used when this parameter is missing.
     * - `proxyAddress` (optional): The proxy server address to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `proxyPort` (optional): The proxy server port to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `proxyUsername` (optional): The proxy server username to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `proxyPassword` (optional): The proxy server password to use when `proxyType` is configured. This optional parameter is to be used with FWD records.
     * - `appName` (optional): This parameter is required for updating the `APP` record.
     *    - `classPath` (optional): This parameter is required for updating the `APP` record.
     * - `recordData` (optional): This parameter is used for updating the `APP` record as per the DNS app requirements.
     * - `rdata` (optional): This parameter is used for updating unknown i.e. unsupported record types. The value must be formatted as a hex string or a colon separated hex string.
     * - `newRData` (optional): This parameter is used for updating unknown i.e. unsupported record types. The new value that must be formatted as a hex string or a colon separated hex string.
     * @return bool
     */
    public function update(array $data){
        $response = $this->API->sendCall($data, "zones/records/update");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }
}
