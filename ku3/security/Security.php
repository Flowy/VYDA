<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 23. 11. 2014
 * Time: 13:57
 */
require_once 'Authentication.php';
require_once 'AuthenticationProvider.php';
require_once 'DatabaseAuthProvider.php';

session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/VYDA/ku3/dbconfig.php";
include_once 'roles.php';

final class Security {

    private static $instance;

    private $authProviders;
    private $AUTH_SESSION_PARAM;
    private $ORIGIN_PATH_SESSION_PARAM;

    public function Security($authParam, $pathParam) {
        $this->AUTH_SESSION_PARAM = $authParam;
        $this->ORIGIN_PATH_SESSION_PARAM = $pathParam;
        $this->initProviders();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new Security('auth', 'originPath');
        }
        return self::$instance;
    }

    private function initProviders() {
        $this->authProviders = array();

        $studentiConf = new DbAuthProviderConfig(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        $studentiConf->setTable('Studenti')->setLoginColumn('kod_studenta')->setPasswordColumn('heslo');
        $this->authProviders[] = new DatabaseAuthProvider($studentiConf, ROLE_STUDENT);

        $profesoriConf = new DbAuthProviderConfig(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        $profesoriConf->setTable('Pedagogove')->setLoginColumn('kod_pedagoga')->setPasswordColumn('heslo');
        $this->authProviders[] = new DatabaseAuthProvider($profesoriConf, ROLE_PROFESOR);
    }

    public function requireRole($role) {
        $auth = $_SESSION[$this->AUTH_SESSION_PARAM];
        if (!isset($auth)) {
            $this->requireLogin();
        } else if (!($auth instanceof Authentication)) {
            $this->accessDenied('authentication broken, saved auth object is of class: ' . get_class($auth));
        } else if (!$auth->hasRole($role)) {
            $this->accessDenied('not allowed for role ' . $role);
        } else {
            return true;
        }
    }

    public function login($username, $password) {
        $auth = null;
        foreach ($this->authProviders as &$authProvider) {
            if ($authProvider instanceof AuthenticationProvider) {
                $auth = $authProvider->authenticate($username, $password);
                if ($auth) {
                    break;
                }
            }
        }
        if (is_null($auth)) {
            $this->accessDenied('invalid credentials');
        } else {
            $_SESSION[$this->AUTH_SESSION_PARAM] = $auth;
            if (isset($_SESSION[$this->ORIGIN_PATH_SESSION_PARAM])) {
                header('Location: ' . $_SESSION[$this->ORIGIN_PATH_SESSION_PARAM]);
                die();
            } else {
                die('parameter: ' . $this->ORIGIN_PATH_SESSION_PARAM . ' does not exists');
            }
        }
    }

    private function requireLogin() {
        $_SESSION[$this->ORIGIN_PATH_SESSION_PARAM] = $_SERVER['REQUEST_URI'];
        header("Location: security/login.html");
        die();
    }

    public function accessDenied($message = null) {
        header("HTTP/1.1 403 Forbidden");
        $response = 'Access Forbidden';
        if ($message) {
            $response .= ': ' . $message;
        }
        die($response);

    }
} 