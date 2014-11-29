<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 29. 11. 2014
 * Time: 15:51
 */
require_once 'security/Security.php';
require_once 'security/roles.php';
session_start();

Security::requireRole(ROLE_STUDENT);