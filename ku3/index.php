<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 23. 11. 2014
 * Time: 14:32
 */
require_once 'security/Security.php';
session_start();

Security::requireRole('user');