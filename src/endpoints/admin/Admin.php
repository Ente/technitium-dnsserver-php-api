<?php
namespace Technitium\DNSServer\API;

use Technitium\DNSServer\API\admin\groups;
use Technitium\DNSServer\API\admin\logs;
use Technitium\DNSServer\API\admin\permissions;
use Technitium\DNSServer\API\admin\sessions;
use Technitium\DNSServer\API\admin\users;

class admin {
    public $API;

    private $users;

    private $sessions;

    private $permissions;

    private $logs;

    private $groups;

    public function __construct(\Technitium\DNSServer\API\API $api){
        $this->API = $api;
        $this->eloader();
    }

    private function eloader(): void{
        require_once __DIR__ . "/Groups.admin.php";
        require_once __DIR__ . "/Logs.admin.php";
        require_once __DIR__ . "/Permissions.admin.php";
        require_once __DIR__ . "/Sessions.admin.php";
        require_once __DIR__ . "/Users.admin.php";
    }

    public function users(): users {
        if(!$this->users) $this->users = new users($this->API);
        return $this->users;
    }

    public function sessions(): sessions {
        if(!$this->sessions) $this->sessions = new sessions($this->API);
        return $this->sessions;
    }

    public function permissions(): permissions {
        if(!$this->permissions) $this->permissions = new permissions($this->API);
        return $this->permissions;
    }

    public function logs(): logs {
        if(!$this->logs) $this->logs = new logs($this->API);
        return $this->logs;
    }

    public function groups(): groups {
        if(!$this->groups) $this->groups = new groups($this->API);
        return $this->groups;
    }
}