<?php
namespace Technitium\DNSServer\API\zones;

class permissions {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `set()` - Set permissions for a zone.
     * @param string $zone The name of the zone to set permissions.
     * @param string $userPermissions (optional): A pipe `|` separated table data with each row containing username and boolean values for the view, modify and delete permissions. For example: user1|true|true|true|user2|true|false|false
     * @param string $groupPermissions (optional): A pipe `|` separated table data with each row containing the group name and boolean values for the view, modify and delete permissions. For example: group1|true|true|true|group2|true|true|false
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function set(string $zone, string $userPermissions = "", string $groupPermissions = ""): bool{
        $response = $this->API->sendCall(["zone" => $zone, "userPermissions" => $userPermissions, "groupPermissions" => $groupPermissions], "zones/permissions/set");
        return $response["status"] == "ok";
    }

    /**
     * `get()` - Get permissions for a zone.
     * @param string $zone The name of the zone to get permissions.
     * @param bool $includeUsersAndGroups (optional) Set to `true` to include users and groups in the response. Default value is `false`.
     * @return array|bool Returns an array of permissions if successful, `false` otherwise.
     */
    public function get(string $zone, bool $includeUsersAndGroups = false): array|bool{
        if($includeUsersAndGroups){$includeUsersAndGroups = "true";} else {$includeUsersAndGroups = "false";}
        $response = $this->API->sendCall(["zone" => $zone, "includeUsersAndGroups" => $includeUsersAndGroups], "zones/permissions/get");
        if($response["status"] == "ok"){
            return $response["permissions"];
        } else {
            return false;
        }
    }
}
