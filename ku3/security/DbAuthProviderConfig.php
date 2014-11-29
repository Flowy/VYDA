<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 23. 11. 2014
 * Time: 16:29
 */

class DbAuthProviderConfig {

    private $dbServer, $login, $password, $database, $table, $loginColumn, $passwordColumn;

    function DbAuthProviderConfig($dbServer, $login, $password, $database, $table = 'authentication', $loginColumn = 'login', $passwordColumn = 'password') {
        $this->dbServer = $dbServer;
        $this->login = $login;
        $this->password = $password;
        $this->database = $database;
        $this->table = $table;
        $this->loginColumn = $loginColumn;
        $this->passwordColumn = $passwordColumn;
    }

    public function getLoginColumn()
    {
        return $this->loginColumn;
    }

    public function setLoginColumn($loginColumn)
    {
        $this->loginColumn = $loginColumn;
        return $this;
    }
    public function getPasswordColumn()
    {
        return $this->passwordColumn;
    }
    public function setPasswordColumn($passwordColumn)
    {
        $this->passwordColumn = $passwordColumn;
        return $this;
    }
    public function getTable()
    {
        return $this->table;
    }
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function connect() {
        return new mysqli($this->dbServer, $this->login, $this->password, $this->database);
    }

}