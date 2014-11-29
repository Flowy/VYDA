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

define('AUTH_SESSION_PARAM', 'auth');
define('ORIGIN_PATH_SESSION_PARAM', 'originPath');

include_once '../dbconfig.php';
include_once 'roles.php';

class Security {

    private static $authProviders;

    private static function initProviders() {
        self::$authProviders = array();

        $studentiConf = new DbAuthProviderConfig(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        $studentiConf->setTable('Studenti')->setLoginColumn('kod_studenta')->setPasswordColumn('heslo');
        self::$authProviders[] = new DatabaseAuthProvider($studentiConf, ROLE_STUDENT);

        $profesoriConf = new DbAuthProviderConfig(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        $profesoriConf->setTable('Pedagogove')->setLoginColumn('kod_pedagoga')->setPasswordColumn('heslo');
        self::$authProviders[] = new DatabaseAuthProvider($profesoriConf, ROLE_PROFESOR);
    }

    public static function requireRole($role) {
        if (!isset($_SESSION[AUTH_SESSION_PARAM])) {
            self::requireLogin();
        } else if (!($_SESSION[AUTH_SESSION_PARAM] instanceof Authentication) || !$_SESSION[AUTH_SESSION_PARAM]->hasRole($role)) {
            self::accessDenied();
        } else {
            return true;
        }
    }

    public static function login($username, $password) {
        if (!isset(self::$authProviders)) {
            self::initProviders();
        }
        $auth = null;
        foreach (self::$authProviders as &$authProvider) {
            if ($authProvider instanceof AuthenticationProvider) {
                $auth = $authProvider->authenticate($username, $password);
                if ($auth) {
                    break;
                }
            }
        }
        if (is_null($auth)) {
            self::accessDenied();
        } else {
            $_SESSION[AUTH_SESSION_PARAM] = $auth;
            header('Location: ' . $_SESSION[ORIGIN_PATH_SESSION_PARAM]);
            die();
        }
    }

    private static function requireLogin() {
        $_SESSION[ORIGIN_PATH_SESSION_PARAM] = $_SERVER['REQUEST_URI'];
        header("Location: security/login.html");
        die();
    }

    public static function accessDenied($message = null) {
        header("HTTP/1.1 403 Forbidden");
        $response = 'Access Forbidden';
        if ($message) {
            $response .= ': ' . $message;
        }
        die($response);

    }
} 