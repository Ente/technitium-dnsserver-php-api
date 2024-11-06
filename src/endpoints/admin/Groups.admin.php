<?php
namespace Technitium\DNSServer\API\admin;

/**
 * Groups class
 *
 * This class is used to interact with the groups endpoint of the Technitium DNS Server API
 */
class groups {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `list()` - Returns a list of all groups.
     * - `PERMISSIONS`: `Administration:View`
     * @return array|bool Returns the result array or an error array
     */
    public function list(){
        $response = $this->API->sendCall([], "admin/groups/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `get()` - Returns the details for a group.
     * - `PERMISSIONS`: `Administration:View`
     * @param string $name The name of the group.
     * @param bool $includeUsers Set to `true` to include the list of users in the group.
     * @return array|bool Returns the result array or false if the group was not found.
     */
    public function get(string $name, bool $includeUsers = true){
        $includeUsers = $includeUsers ? "true" : "false";
        $response = $this->API->sendCall(["group" => $name, "includeUsers" => "true"], "admin/groups/get");
        if($response["response"]["name"] == $name){
            return $response["response"];
        }
        return false;
    }

    /**
     * `create()` - Creates a new group.
     * - `PERMISSIONS`: `Administration:Modify`
     * @param string $name The name of the group to create.
     * @param string $description The description text for the group (optional).
     * @return bool Returns `true` if the group was created successfully, otherwise `false`.
     */
    public function create(string $name, string $description = null){
        $response = $this->API->sendCall(["group" => $name, "description" => $description], "admin/groups/create");
        return $response["response"]["name"] == $name;
    }

    /**
     * `set()` - Allows chaning a group description or rename a group
     * - `PERMISSIONS`: `Administration:Modify`
     * @param string $name The name of the group to update.
     * @param string $newName The new group name to rename the group (optional).
     * @param string $description A new group description (optional).
     * @param array $members An array of users to add to the group (optional).
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function set(string $name, string $newName = null, string $description = null, array $members = []){
        $response = $this->API->sendCall(["group" => $name, "newGroup" => $newName, "description" => $description, "members" => implode(",", $members)], "admin/groups/set");
        if($response["response"]["name"] == $name){
            return $response["response"];
        }
        return false;
    }

    /**
     * `delete()` - Deletes a group.
     * - `PERMISSIONS`: `Administration:Delete`
     * @param string $name The name of the group to delete.
     * @return bool Returns the result array or an error array.
     */
    public function delete(string $name){
        $response = $this->API->sendCall(["group" => $name], "admin/groups/delete");
        return $response["status"] == "ok";
    }
}
