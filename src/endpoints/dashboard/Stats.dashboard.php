<?php
namespace Technitium\DNSServer\API\dashboard;

class stats {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `get()` - List all DNS stats
     * @param string $type Duration type, valid options ["LastHour", "LastDay, "LastWeek", "LastMonth", "LastYear, "Custom"]
     * @param string $utc True to return main chart with data labels in UTC date time format
     * @param string $start start date for `custom` type. ISO 8601 format.
     * @param string $end end date for `custom` type. ISO 8601 format.
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function get(string $type = "LastHour", bool $utc = true, string $start = "", string $end = ""){
        if($utc){$utc="true";}else{$utc="false";};
        $response = $this->API->sendCall(["type" => $type, "utc" => $utc, "start" => $start, "end" => $end], "cache/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `getTop()` - Get top statistics for specified types
     * @param string $type Duration type, valid options ["LastHour", "LastDay, "LastWeek", "LastMonth", "LastYear, "Custom"]
     * @param string $statsType Type of stats to get, valid options ["TopClients", "TopDomains", "TopBlockedDomains"]
     * @param int $limit Limit of results to return. Default is 1000
     * @param bool $noReverseLookup True to disable reverse lookup for client IP (only if statsType is TopClients)
     * @param bool $onlyRateLimitedClients True to return only rate limited clients (only if statsType is TopClients)
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function getTop(string $type = "LastHour", string $statsType = "TopClients", int $limit = 1000, bool $noReverseLookup = false, bool $onlyRateLimitedClients = false){
        $response = $this->API->sendCall(["type" => $type, "statsType" => $statsType, "limit" => $limit, "noReverseLookup" => $noReverseLookup, "onlyRateLimitedClients" => $onlyRateLimitedClients], "dashboard/stats/getTop");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `delete()` - Delete all DNS statistics
     * @return bool Returns `true` if successful, `false` otherwise.
     */
    public function deleteAll(){
        $response = $this->API->sendCall([], "dashboard/stats/deleteAll");
        return $response["status"] == "ok";
    }
}
