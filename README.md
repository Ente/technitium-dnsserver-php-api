# technitium-dnsserver-php-api

This API client is intended to be used with Technitiums DNS Server

## Installation

Via Git: `git clone https://github.com/ente/technitium-dnsserver-php-api.git` or `composer require ente/technitium-dnsserver-php-api`

Then: `require_once "/path/to/vendor/autoload.php";` & `use Technitium\DNSServer\API\API;`
Then: `require_once "/path/to/API.dnsserver.ente.php";` & `use Technitium\DNSServer\API\API;`

## Configuration

### .env

- `API_URL`: The API URL of your Technitium DNS Server (with port), e.g. `localhost:5380`, `192.168.1.2:5380` or `server.domain.tld:5380`
- `USERNAME`: The username for the user account. (You should create a dedicated one)
- `PASSWORD`: The password for the user account.
- `INCLUDE_INFO`: Returns basic information that might be relevant for the queried request.
- `TOKEN`: Your API token, if already existent. (`[Your Username]` > `[Create API Token]`)
- `USE_POST`: Specify if you want to access the API via POST (`true`) instead of GET (`false`) in default.
- `USE_HTTPS`: Enable (`true`) HTTPS for the API connection.

## General Usage

```php
require_once "/vendor/autoload.php";
use Technitium\DNSServer\API;

$api = new API();

// Get all zones
$zones = $api->zones()->get();
// Get all zone records
$records = $api->zones->records()->get("example.com");

// Install an app

$sampleApp = $api->apps()->listStoreApps()["storeApps"][0];
if($api->apps->install($sampleApp["name"])) {
    echo "App installed successfully!";
}

// OR

$sampleApp = $api->apps()->listStoreApps()["storeApps"][0];
if($api->apps->downloadAndInstall($sampleApp["name"], $sampleApp["url"])) {
    echo "App installed successfully!";
}

```

## DDNS

You can use the `DDNS.Helper.API.dnsserver.ente.php` class to configure records to point to the current IP address.

```php
<?php

require_once "/vendor/autoload.php";
use Technitium\DNSServer\API;
use Technitium\DNSServer\API\Helper\DDNS;

$path_to_configJSON = "/my/config.json";
$ddns = new DDNS(new API());
$ddns->updateRecords($path_to_configJSON);

// OR

$ddns_result = new DDNS(new API(), file_get_contents($path_to_configJSON)); // starts automatically updating the records

// OR
$api = new API();
$ddns_result = $api->ddns()->updateRecords($path_to_configJSON);

```

Your DDNS configuration file should look like this:

```json
{
    "domanin": "example.com",
    "records": [
        "sub.example.com"
    ]
}
```

You can also set up multiple environments by using different configuration files:

```php
<?php

require_once "/vendor/autoload.php";
use Technitium\DNSServer\API;
use Technitium\DNSServer\API\Helper\DDNS;

DDNS(new API(), file_get_contents("/my/config.json"));
DDNS(new API(), file_get_contents("/my/config2.json"));
DDNS(new API("/my/.env"), file_get_contents("/my/config3.json"));

```

## Changes

### v1.0: Initial Release

- initial release supporting each endpoint
