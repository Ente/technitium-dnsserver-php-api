# technitium-dnsserver-php-api

This API client is intended to be used with Technitiums DNS Server
For the full Technitium API Documentation please visit [Technitium API Documentation](https://github.com/TechnitiumSoftware/DnsServer/blob/master/APIDOCS.md)

## Installation

### Via Git

Run `git clone https://github.com/ente/technitium-dnsserver-php-api.git` where ever you want the library to be located.

Then `require_once "/path/to/API.dnsserver.ente.php";` & `use Technitium\DNSServer\API\API;` in your PHP file.

### Via Composer

Run `composer require ente/technitium-dnsserver-php-api`

Then: `require_once "/path/to/vendor/autoload.php";` & `use Technitium\DNSServer\API\API;`

## Configuration

### .env

- `API_URL`: The API URL of your Technitium DNS Server (with port), e.g. `localhost:5380`, `192.168.1.2:5380` or `server.domain.tld:5380`
- `USERNAME`: The username for the user account. (You should create a dedicated one)
- `PASSWORD`: The password for the user account.
- `INCLUDE_INFO`: Returns basic information that might be relevant for the queried request.
- `TOKEN`: Your API token, if already existent. (`[Your Username]` > `[Create API Token]`). If left empty, the API will use the username and password for authentication to create an API token for you and will write it to your `.env`.
- `USE_POST`: Specify if you want to access the API via POST (`true`) instead of GET (`false`) in default.
- `USE_HTTPS`: Enable (`true`) HTTPS for the API connection. If your server does not support HTTPS, the API will simply return `false` to all requests.

## General Usage

```php
require_once "/vendor/autoload.php";
use Technitium\DNSServer\API;

$api = new API("path/to/env", "env-name");

// Get all zones
$zones = $api->zones()->get();
// Get all zone records
$records = $api->zones()->records()->get("example.com");

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

### Send to custom endpoint

```php
<?php

require_once "/vendor/autoload.php";
use Technitium\DNSServer\API;

$api = new API("path/to/env", "env-name");
// You have to set <bool>$bypass to true to use this feature
echo var_dump($api->sendCall(data: array("field" => "value"), endpoint: "admin/users/list", skip: false, bypass: true))

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

$ddns_result = new DDNS(new API("path/to/env"), file_get_contents($path_to_configJSON)); // starts automatically updating the records

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

DDNS(new API("path/to/env"), file_get_contents("/my/config.json"));
DDNS(new API(__DIR__), file_get_contents("/my/config2.json"));
DDNS(new API(__DIR__ . "/configurations", ".env-custom"), file_get_contents("/my/config3.json"));

```

## Changes

### v1.1.4: Type safety

- Added type safety to the `DDNS.Helper.API.dnsserver.ente.php` class
- Added type safety to all other classes
- Fixed incorrect/Added API docs

### v1.1.3: Shell safe

- Library is now shell safe (you are now required to specify the path to the `.env` file)
- Silenced most $_SERVER['argv'] warnings when running the library in shell

### v1.1.2: Quality

- Added more documentation to the classes
- Small code changes
<!-- Removed whitespaces -->

### v1.1.1: Fixes

- Small changes to the `README.md`
- Added code documentation within the classes
- Implemented the `<bool>$bypass` parameter to the sendCall function to bypass the `prepareEndpoint` function therefore allowing to send to custom endpoints
- Adjusted class functions scope

### v1.1: Fixes

- `TOKEN` can now be empty
- Fixed some endpoints not working
- Fixed issues preventing loading different environments
- Exception handling

### v1.0: Initial Release

- initial release supporting each endpoint
