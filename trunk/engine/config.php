<?php
/******Constant.php*******
-- Consist of server related defination.
-- Revision: Beta 4
-- Author: akakori
-- Homepage: -
-- Beta Server: -
-- Please do not remove this section
************************/

//Server Stats Defination
define("SERVER_NAME", "test");
define("SPEED", 1); //1 (Normal), 3 (3x Speed)
define("USER_TIMEOUT", 10); //Time in Minutes
define("WORLD_MAX", 400); //To put it simply. Eg 5, Max x = +/-5 y = +/-5

//Server Preference Settings
define("GP_ENABLE", false); //Graphic Pack Usage
define("ACTIVATE", 0); //Activation of account
define("COOKIE_EXPIRE", 60*60*24*7);  //7 days by default
define("COOKIE_PATH", "/");  //Available in whole domain
define("SUBDOMAIN", 0); //Is the server on a subdomain?
define("INCLUDE_ADMIN", false); //Include admin on ranking?

//SQL Server Defination
define("SQL_SERVER", "");
define("SQL_USER", "");
define("SQL_PASS", "!");
define("SQL_DB", "");
define("TB_PREFIX", "s1");
define("CONNECT_TYPE", 0); // 0 = MYSQL, 1 = MYSQLi

//User Access Defination
define("BANNED", 0);
define("ADMIN", 9);
define("MODERATOR", 8);
define("PLUS", 2);
define("USER", 2);

//Admin Email Defination
define("ADMIN_NAME", "admin");
define("ADMIN_EMAIL", "spixx@b-landia.net");
?>
