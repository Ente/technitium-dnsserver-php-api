<?php
namespace Technitium\DNSServer\API;
use Technitium\DNSServer\API\users\profile;
use Technitium\DNSServer\API\users\session;

class users extends API {
    public $API;

    private $profile;

    private $session;

    public function __construct($api){
        $this->API = $api;
        $this->eloader();
    }

    private function eloader(){
        try {
            require_once __DIR__ . "/Profile.users.php";
            require_once __DIR__ . "/Session.users.php";
        } catch (\Throwable $th) {
             Helper\Log::error_rep("Failed to load required enpoint classes: " . $th->getMessage());
        }
    }


    /**
     * `changePassword()` - Change the password of the current user.
     * @param string $newPassword New password to be set.
     * @return bool Returns `true` if password was changed successfully.
     */
    public function changePassword(string $newPassword){
        try {
            $response = $this->sendCall(["pass" => $newPassword], "user/changePassword");
            return $response["status"] == "ok";
        } catch (\Throwable $th) {
            Helper\Log::error_rep("Failed to change password: " . $th->getMessage());
            return false;
        }
    }

    /**
     * `createToken()` - Create a new non-expiring API session token.
     * @return array|bool Returns a API token or `false` otherwise.
     */
    public function createToken(string $username, string $password, string $tokenName){
        try {
            $response = $this->sendCall(["user" => $username, "pass" => $password, "tokenName" => $tokenName], "user/createToken");
            if($response["status"] == "ok"){
                return $response["response"];
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Helper\Log::error_rep("Failed to create token: " . $th->getMessage());
            return false;
        }
    }

    /**
     * `login()` - Login with username and password to retrieve a API session token.
     * @return array|bool Returns relevant information and the token.
     */
    public function login(string $username, string $password){
        try {
            $response = $this->API->sendCall(["user" => $username, "pass" => $password, "includeInfo" => "true"], "user/login");
            if($response["status"] == "ok"){
                return $response;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Helper\Log::error_rep("Failed to login: " . $th->getMessage(), $th->getTraceAsString());
            return false;
        }
    }

    /**
     * `logout()` - Logout from the current session. Making the current token invalid.
     * @return array|bool Returns relevant information and the token.
     */
    public function logout(){
        try {
            $response = $this->sendCall([], "users/logout");
            return $response["status"] == "ok";
        } catch (\Throwable $th) {
            Helper\Log::error_rep("Failed to logout: " . $th->getMessage());
            return false;
        }
    }

    public function profile(): profile {
        if(!$this->profile) $this->profile = new profile($this->API);
        return $this->profile;
    }

    public function session(): session {
        if(!$this->session) $this->session = new session($this->API);
        return $this->session;
    }
    
}
