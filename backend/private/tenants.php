<?php

require_once('database.php');

// tenants table: id, username, email, first_name, last_name, building_id

class Tenant {

    private $id, $email, $first_name, $last_name, $building_id, $mobile_number, $room_number;

    public function __construct($email='', $first_name='', $last_name='', $building_id=0, $mobile_number=0, $room_number=0) {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->building_id = $building_id;
        $this->mobile_number = $mobile_number;
        $this->room_number = $room_number;
    }

    public function check_tenant() {
        global $db;

        $sql = 'select id from tenants where email = ?';
        if(!$stmt = $db->query($sql))
            return false;
        $stmt->bind_param('s', $this->email);
        if(!$stmt->execute())
            return false;
        $stmt->store_result();
        if($stmt->num_rows < 1)
            return false;
        return true;
    }
    public function add_tenant() {
        global $db;

        $sql = 'insert into tenants (email, first_name, last_name, building_id, mobile_number, room_number) values (?, ?, ?, ?, ?, ?)';
        if(!$stmt = $db->query($sql)) {
            return false;
        }
        $stmt->bind_param('sssiii', $this->email, $this->first_name, $this->last_name, $this->building_id, $this->mobile_number, $this->room_number);
        if(!$stmt->execute())
            return false;
        return true;
    }
    public function get_tenant_info($email='') {
        global $db;

        $tenant_info = array();

        $sql = "select id, first_name, last_name, mobile_number, room_number, building_id from tenants where email = ?";
        if(!$stmt = $db->query($sql))
            return false;
        $stmt->bind_param('s', $email);
        if(!$stmt->execute())
            return false;
        $stmt->bind_result($this->id, $this->first_name, $this->last_name, $this->mobile_number, $this->room_number, $this->building_id);
        $stmt->fetch();
        array_push($tenant_info, $this->id, $this->first_name, $this->last_name, $this->mobile_number, $this->room_number, $this->building_id);
        return $tenant_info;
    }
    public function get_tenants($building_id)
    {
        global $db;

        $tenant = array();
        $tenants = array();

        $sql = "select id, email, room_number from tenants where building_id = ?";
        if(!$stmt = $db->query($sql))
            return false;
        $stmt->bind_param('i', $building_id);
        if(!$stmt->execute())
            return false;
        $stmt->bind_result($id, $email, $room_number);
        while($stmt->fetch()) {
            array_push($tenant, $id, $email, $room_number);
            array_push($tenants, $tenant);
            $tenant = array();
        }
        return $tenants;
    }
}

?>