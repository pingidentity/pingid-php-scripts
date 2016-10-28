<?php
/*
	PingID API Sample PHP Script: authenticateOnline.php
	
	This script will execute the PingID API AuthenticateOnline operation to authenticate
	a PingID user online. For more information, review the API documentation:
	https://developer.pingidentity.com/en/api/pingid-api.html

	Note:	This software is open sourced by Ping Identity but not supported commercially
			as such. Any questions/issues should go to the Github issues tracker or discuss
			on the [Ping Identity developer communities] . See also the DISCLAIMER file in
			this directory.
*/

require_once 'Utils.php';

if (count($argv) < 2) {
	echo "Usage: $argv[0] <username>\n";
	exit;
}

print pingid_exec_command('pingid.properties', 'authonline', array(		
		'authType' => 'CONFIRM',
		'spAlias' => 'web',
		'userName' => $argv[1]
));

?>
