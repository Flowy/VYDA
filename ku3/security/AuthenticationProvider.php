<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 23. 11. 2014
 * Time: 15:20
 */

interface AuthenticationProvider {
    function authenticate($login, $password);
} 