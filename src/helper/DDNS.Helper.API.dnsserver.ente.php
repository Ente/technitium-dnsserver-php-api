<?php

namespace Technitium\DNSServer\API\Helper;
use Technitium\DNSServer\API\Helper\Log;

class DDNS {
    private $API;

    public function __construct($api, $configFile = null){
        $this->API = $api;
        if ($configFile != null) {
            $this->updateRecords($configFile);
        }
    }

    public function getPublicIP($uri = "https://ipecho.net/plain") {
        return trim(file_get_contents($uri));
    }

    public function updateRecords(string $configFile) {
        $config = json_decode(file_get_contents($configFile));
        $records = $config->records;

        foreach ($records as $record) {
            $this->processRecord($record, $config->domain);
        }

        Log::error_rep("Finished updating records!");
        return true;
    }

    private function processRecord($record, $domain) {
        $allrecords = $this->API->zones()->records()->get($record)["records"];
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

    private function updateRecord($record, $domain, $newIP) {
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

    private function addRecord($record, $domain, $ipAddress) {
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
