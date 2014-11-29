<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 23. 11. 2014
 * Time: 15:25
 */
require_once 'AuthenticationProvider.php';
require_once 'DbAuthProviderConfig.php';

include_once 'roles.php';

class DatabaseAuthProvider implements AuthenticationProvider {

    private $dbConfig;
    private $givingRole;

    function DatabaseAuthProvider(DbAuthProviderConfig $config, $role) {
        $this->dbConfig = $config;
        $this->givingRole = $role;
    }

    function authenticate($login, $password) {
        $user = $this->fetchAssocLoginData($login);
        if ($user && $user[$this->dbConfig->getPasswordColumn()] === $password) {
            return new Authentication($login, $this->givingRole);
        } else {
            return null;
        }
    }

    private function fetchAssocLoginData($login) {
        $connection = $this->dbConfig->connect();
        if ($connection->errno) {
            die('Connection to database failed: ' . $connection->error);
        }
        $queryString =
            'SELECT ' . $this->dbConfig->getLoginColumn() . ', ' . $this->dbConfig->getPasswordColumn()
            . ' FROM ' . $this->dbConfig->getTable()
            . ' WHERE ' . $this->dbConfig->getLoginColumn() . ' = \'' . $login . '\'';
        $queryResult = mysqli_query($connection, $queryString);
        if ($queryResult) {
            $result = mysqli_fetch_assoc($queryResult);
        } else {
            $result = false;
        }
        $queryResult->close();
        $connection->close();
        return $result;
    }
}