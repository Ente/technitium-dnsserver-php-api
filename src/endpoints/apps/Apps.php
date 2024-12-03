<?php
namespace Technitium\DNSServer\API;
use Technitium\DNSServer\API\apps\config;

class apps extends API {
    public $API;

    private $config;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
        $this->eloader();
    }

    private function eloader(): void{
        require_once __DIR__ . "/Config.apps.php";
    }

    /**
     * `downloadAndInstall()` - Downloads and installs an app from a URL.
     * @param string $name The name of the app.
     * @param string $url The URL of the app. (Must start with `https://`)
     * @return array|bool Returns the result array
     */
    public function downloadAndInstall(string $name, string $url): array|bool{
        $response = $this->API->sendCall(["name" => $name, "url" => $url], "apps/downloadAndInstall");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `list()` - Returns a list of all installed apps.
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function list(): array|bool{
        $response = $this->API->sendCall([], "apps/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `downloadAndUpdate()` - Downloads and updates an app from a URL.
     * @param string $name The name of the app.
     * @param string $url The URL of the app. (Must start with `https://`)
     * @return array|bool Returns the result array
     */
    public function downloadAndUpdate(string $name, string $url): array|bool{
        $response = $this->API->sendCall(["name" => $name, "url" => $url], "apps/downloadAndUpdate");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `install()` - Installs an app from the store.
     * @param string $name The name of the app to install.
     * @return bool Returns `true` if the app was installed successfully.
     */
    public function install(string $name): bool{
        $response = $this->API->sendCall(["name" => $name], "apps/install");
        return $response["status"] == "ok";
    }

    /**
     * `listStoreApps()` - Returns a list of all store apps.
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function listStoreApps(): array|bool{
        $response = $this->API->sendCall([], "apps/listStoreApps");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `uninstall()` - Uninstalls an app.
     * @param string $name The name of the app to uninstall.
     * @return bool Returns `true` if the app was uninstalled successfully.
     */
    public function uninstall(string $name): bool{
        $response = $this->API->sendCall(["name" => $name], "apps/uninstall");
        return $response["status"] == "ok";
    }

    /**
     * `update()` - Updates an app.
     * @param string $name The name of the app to update.
     * @return bool Returns `true` if the app was updated successfully.
     */
    public function update(string $name): bool{
        $response = $this->API->sendCall(["name" => $name], "apps/update");
        return $response["status"] == "ok";
    }

    /**
     * `config()` - Returns the config object.
     * @param string $name The name of the app.
     * @return config Returns the config object.
     */
    public function config(string $name): config {
        if(!$this->config) $this->config = new config($this->API);
        return $this->config;
    }
}
