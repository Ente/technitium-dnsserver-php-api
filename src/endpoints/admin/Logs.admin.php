<?php
namespace Technitium\DNSServer\API\admin;

class logs {
    public $API;

    public function __construct($api){
        $this->API = $api;
    }

    /**
     * `list()` - Returns a list of all log files.
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function list(){
        $response = $this->API->sendCall([], "admin/logs/list");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `query()` - Queries a log file.
     * @param array $data The data to query the log file with. All options:
     * - `classPath`: The class path of the DNS app.
     * - `pageNumber` (optional): The page number of the data set to retrieve.
     * - `entriesPerPage` (optional): The number of entries per page.
     * - `descendingOrder` (optional): Orders the selected data set in descending order.
     * - `start` (optional): The start date time in ISO 8601 format to filter the logs.
     * - `end` (optional): The end date time in ISO 8601 format to filter the logs.
     * - `clientIpAddress` (optional): The client IP address to filter the logs.
     * - `protocol` (optional): The DNS transport protocol to filter the logs. Valid values are [`Udp`, `Tcp`, `Tls`, `Https`, `Quic`].
     * - `responseType` (optional): The DNS server response type to filter the logs. Valid values are [`Authoritative`, `Recursive`, `Cached`, `Blocked`, `UpstreamBlocked`, `CacheBlocked`].
     * - `rcode` (optional): The DNS response code to filter the logs.
     * - `qname` (optional): The query name (QNAME) in the request question section to filter the logs.
     * - `qtype` (optional): The DNS resource record type (QTYPE) in the request question section to filter the logs.
     * - `qclass` (optional): The DNS class (QCLASS) in the request question section to filter the logs.
     * @return array Returns the result array or `false` if the group was not found.
     */
    public function query($data){
        return $this->API->sendCall($data, "admin/logs/query");
    }

    /**
     * `download()` - Downloads a log file.
     * @param string $fileName The name of the file to download.
     * @param int $limit The number of mb to limit the download to.
     * @return array|bool Returns the result array or `false` if the group was not found.
     */
    public function download(string $fileName, int $limit = 0){
        $response = $this->API->sendCall(["fileName" => $fileName, "limit" => $limit], "admin/logs/download");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }

    /**
     * `delete()` - Deletes a log file.
     * @param string $log The name of the file to delete.
     * @return bool Returns `true` if the file was deleted or `false` if the file was not found.
     */
    public function delete(string $log){
        $response = $this->API->sendCall(["log" => $log], "admin/logs/delete");
        return $response["status"] == "ok";
    }

    /**
     * `deleteAll()` - Deletes all log files.
     * @return bool Returns `true` if the files were deleted or `false` if the files were not found.
     */
    public function deleteAll(){
        $response = $this->API->sendCall([], "admin/logs/deleteAll");
        return $response["status"] == "ok";
    }
}
