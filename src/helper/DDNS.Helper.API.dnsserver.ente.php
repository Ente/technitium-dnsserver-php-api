<?php

namespace Technitium\DNSServer\API\Helper;
use Technitium\DNSServer\API\Helper\Log;

class DDNS {
    private \Technitium\DNSServer\API\API $API;

    public function __construct(\Technitium\DNSServer\API\API $api, string $configFile = null){
        $this->API = $api;
        if ($configFile != null) {
            $this->updateRecords($configFile);
        }
    }

    /**
     * `getPublicIP()` function is used to get the public IP address of the server.
     * @param string $uri The URI to get the public IP address from. Currently, it is set to "https://ipecho.net/plain".
     * @return string The public IP address of the server.
     */
    public function getPublicIP(string $uri = "https://ipecho.net/plain"): string {
        return trim(file_get_contents($uri));
    }

    /**
     * `updateRecords()` function is used to update the records in the DNS server.
     * @param string $configFile The path to the configuration file.
     * @return bool Returns true if the records are updated successfully.
     */
    public function updateRecords(string $configFile) {
        $config = json_decode(file_get_contents($configFile));
        $records = $config->records;

        foreach ($records as $record) {
            $this->processRecord($record, $config->domain);
        }

        Log::error_rep("Finished updating records!");
        return true;
    }

    /**
     * `processRecord()` function is used to process the record.
     * @param string $record The record to be processed.
     * @param string $domain The domain the record belongs to.
     * @return void Either adds or updates the record.
     */
    private function processRecord(string $record, string $domain): void {
        try {
            $allrecords = $this->API->zones()->records()->get($record)["records"];
        } catch (\Throwable $th) {
            Log::error_rep("Failed to get records for domain {$record}!");
            return;
        }
        $currentIP = $this->getPublicIP();
        $recordFound = false;

        foreach ($allrecords as $r) {
            // Check if the record already exists as an "A" type record
            if ($r["name"] === $record && $r["type"] === "A") {
                $recordFound = true;
                // Update only if the IP address differs
                if ($r["rData"]["ipAddress"] !== $currentIP) {
                    $this->updateRecord($record, $domain, $currentIP);
                } else {
                    Log::error_rep("No update needed for record {$record}.");
                }
                break;
            }
        }

        // Add the record if it doesn't exist
        if (!$recordFound) {
            $this->addRecord($record, $domain, $currentIP);
        }
    }

    /**
     * `updateRecord()` function is used to update the record.
     * @param string $record The record to be updated.
     * @param string $domain The domain the record belongs to.
     * @param string $newIP The new IP address to be used.
     * @return void Updates the record.
     */
    private function updateRecord(string $record, string $domain, string $newIP): void {
        if (!$this->API->zones()->records()->update([
            "domain" => $record,
            "type" => "A",
            "zone" => $domain,
            "newIpAddress" => $newIP
        ])) {
            Log::error_rep("Failed to update record for domain {$record}!");
        } else {
            Log::error_rep("Updated record for domain {$record}!");
        }
    }

    /**
     * `addRecord()` function is used to add the record.
     * @param string $record The record to be added.
     * @param string $domain The domain the record belongs to.
     * @param string $ipAddress The IP address to be used.
     * @return void Adds the record.
     */
    private function addRecord(string $record, string $domain, string $ipAddress): void {
        if (!$this->API->zones()->records()->add([
            "domain" => $record,
            "type" => "A",
            "zone" => $domain,
            "ipAddress" => $ipAddress
        ])) {
            Log::error_rep("Failed to add record for domain {$record}!");
        } else {
            Log::error_rep("Added record for domain {$record}!");
        }
    }
}
