<?php
namespace Technitium\DNSServer\API\admin;

class sessions {

    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `createToken()` - Creates a new session token for a user.
     * - `PERMISSIONS`: `Administration:Modify`
     * @param string $username The username of the user to create the token for.
     * @param string $tokenName The name of the token to create.
     * @return array|bool Returns the result array or `false` if the request failed.
     */
    public function createToken(string $username, string $tokenName): array|bool{
        $response = $this->API->sendCall(["user" => $username, "tokenName" => $tokenName], "admin/sessions/create", "POST");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `delete()` - Deletes a session token.
     * - `PERMISSIONS`: `Administration:Modify`
     * @param string $partialToken The partial token to delete (Can be obtained from `list()`).
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function delete(string $partialToken): bool{
        $response = $this->API->sendCall(["partialToken" => $partialToken], "admin/sessions/delete", "POST");
        return $response["status"] == "ok";
    }

    /**
     * `list()` - Returns the list of session tokens.
     * - `PERMISSIONS`: `Administration:View`
     * @return array|bool Returns the list of session tokens or `false` if the request failed.
     */
    public function list(): array|bool{
        $response = $this->API->sendCall([], "admin/sessions/list", "GET");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }
}
