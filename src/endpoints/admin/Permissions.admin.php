<?php
namespace Technitium\DNSServer\API\admin;

/**
 * Permissions Admin class
 * Used to interact with the permissions endpoint of the Technitium DNS Server API
 */
class permissions {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `list()` - Returns a list of all permissions.
     * - `PERMISSIONS`: `Administration:View`
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function list(){
        $response = $this->API->sendCall([], "admin/permissions/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `get()` - Returns the permissions for a specified section.
     * @param string $section The section to get permissions for.
     * @param bool $includeUsersAndGroups Set to `true` to include the list of users and groups with permissions. (optional)
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function get(string $section, bool $includeUsersAndGroups = false){
        if($includeUsersAndGroups){ $includeUsersAndGroups = "true"; } else { $includeUsersAndGroups = "false"; }
        $response = $this->API->sendCall(["section" => $section, "includeUsersAndGroups" => $includeUsersAndGroups], "admin/permissions/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `set()` - Sets the permissions for a specified section.
     * @param string $section The section to set permissions for.
     * @param string $userPermissions The permissions to set for users (pipe seperated table data with each row containing usernames and bools for view, modify and delete permission, e.g. `admin|true|true|false|test1|true|false|false`). (optional)
     * @param string $groupPermissions The permissions to set for groups (pipe seperated table data with each row containing group names and bools for view, modify and delete permission, e.g. `Administrators|true|true|false|Example Group|true|false|false`). (optional)
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function set(string $section, string $userPermissions = null, string $groupPermissions = null){
        $response = $this->API->sendCall(["section" => $section, "userPermissions" => $userPermissions, "groupPermissions" => $groupPermissions], "admin/permissions/set");
        if($response["response"]["section"] == $section){
            return $response["response"];
        } else {
            return false;
        }
    }
}
