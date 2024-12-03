<?php
namespace Technitium\DNSServer\API\users;

class session {
    public $API;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
    }

    /**
     * `delete()` - Delete a user session.
     * @param string $partialToken Partial token as returned by users->createToken()
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function delete(string $partialToken): array|bool{
        $response = $this->API->sendCall(["partialToken" => $partialToken], "users/session/delete");
        return $response["status"] == "ok";
    }

    /**
     * `get()` - Get user session information
     * @return array|bool Returns an result array or `false` otherwise.
     */
    public function get(): array|bool{
        $response = $this->API->sendCall([], "users/session/get");
        if($response["status"] == "ok"){
            return $response["response"];
        } else {
            return false;
        }
    }
}
