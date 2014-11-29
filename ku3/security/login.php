<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 23. 11. 2014
 * Time: 16:09
 */
require_once 'Security.php';

if (isset($_POST['login']) && isset($_POST['password'])) {
    Security::getInstance()->login($_POST['login'], $_POST['password']);
} else {
    Security::getInstance()->accessDenied('Credentials not provided');
}