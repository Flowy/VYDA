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

class Security {

    private static $authProviders;

    private static function initProviders() {
        self::$authProviders = array();
        self::$authProviders[] = new DatabaseAuthProvider();
        self::$authProviders[] = new DatabaseAuthProvider();
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

    private static function accessDenied() {
        header("HTTP/1.1 403 Forbidden");
        die("Access Forbidden");
    }
} 