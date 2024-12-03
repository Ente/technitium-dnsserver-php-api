<?php
namespace Technitium\DNSServer\API\admin;

class users {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `list()` - Returns the list of users.
     * - `PERMISSIONS`: `Administration:View`
     * @return array|bool Returns the list of users or `false` if the request failed.
     */
    public function list(): array|bool{
        $response = $this->API->sendCall([], "admin/users/list", "GET");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `create()` - Creates a new user.
     * - `PERMISSIONS`: `Administration:Modify`
     * @param string $username The username of the user to create.
     * @param string $password The password of the user to create.
     * @param string $displayName The display name of the user to create.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function create(string $username, string $password, string $displayName = ""): bool{
        $response = $this->API->sendCall(["user" => $username, "pass" => $password, "displayName" => $displayName], "admin/users/create", "POST");
        return $response["status"] == "ok";
    }

    /**
     * `set()` - Changes the details of a user.
     * - `PERMISSIONS`: `Administration:Modify`
     * @param string $username The username of the user to change the details for.
     * @param string $displayName The new display name of the user.
     * @param string $newUser The new username of the user.
     * @param bool $disabled Whether the user should be disabled or not.
     * @param int $sessionTimeoutSeconds The session timeout in seconds.
     * @param string $newPassword The new password of the user.
     * @param int $iterations The number of iterations to use for the password hashing.
     * @param array $memberOfGroups The groups the user should be a member of.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function set(string $username, string $displayName = "", string $newUser = "", bool $disabled = false, int $sessionTimeoutSeconds = 0, string $newPassword = "", int $iterations = 5, array $memberOfGroups = []): bool{
        if($disabled){$disabled = "true";}else{$disabled = "false";}
        $response = $this->API->sendCall(["user" => $username, "displayName" => $displayName, "newUser" => $newUser, "disabled" => $disabled, "sessionTimeoutSeconds" => $sessionTimeoutSeconds, "newPass" => $newPassword, "iterations" => $iterations, "memberOfGroups" => implode(",", $memberOfGroups)], "admin/users/set", "POST");
        return $response["status"] == "ok";
    }

    /**
     * `deleteUser()` - Deletes a user.
     * - `PERMISSIONS`: `Administration:Modify`
     * @param string $username The username of the user to delete.
     * @return bool Returns `true` if the request was successful, `false` otherwise.
     */
    public function delete(string $username): bool{
        $response = $this->API->sendCall(["user" => $username], "admin/users/delete", "POST");
        return $response["status"] == "ok";
    }

    /**
     * `get()` - Returns the user details.
     * - `PERMISSIONS`: `Administration:View`
     * @param string $username The username of the user to get the details for.
     * @param bool $includeGroups Whether to include the groups the user is a member of.
     * @return array|bool Returns the user details or `false` if the request failed.
     */
    public function get(string $username, bool $includeGroups = false): array|bool{
        if($includeGroups){$includeGroups = "true";} else {$includeGroups = "false";}
        $response = $this->API->sendCall(["user" => $username, "includeGroups" => $includeGroups], "admin/users/get", "GET");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }
}
