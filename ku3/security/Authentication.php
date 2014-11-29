<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 23. 11. 2014
 * Time: 14:00
 */

class Authentication {

    private $username;
    private $role;

    public function Authentication($username, $role) {
        $this->username = $username;
        $this->role = $role;
    }

    public function hasRole($role) {
        return $role === $this->role;
    }

    public function getUsername() {
        return $this->username;
    }
} 