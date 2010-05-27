<?php
/*** Comments ***
* 2010-05-21 - Cleanup complete (fixed capitals)
*
*
*/

include("config.php");

switch(DB_TYPE) {
	case 1:
	include("database/db_MYSQLi.php");
	break;
	//case 2:
	//include("database\db_MSSQL.php");
	//break;
	default:
	include("database/db_MYSQL.php");
	break;
}
?>
