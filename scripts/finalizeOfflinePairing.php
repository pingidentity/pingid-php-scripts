<?php
/*
	PingID API Sample PHP Script: finalizeOfflinePairing.php
	
	This script will finalize offline pairing via the PingID API FinalizeOfflinePairing operation.
	For more information, review the API documentation:
	https://developer.pingidentity.com/en/api/pingid-api.html

	Note:	This software is open sourced by Ping Identity but not supported commercially
			as such. Any questions/issues should go to the Github issues tracker or discuss
			on the [Ping Identity developer communities] . See also the DISCLAIMER file in
			this directory.
*/

require_once 'Utils.php';

if (count($argv) < 3) {
	echo "Usage: $argv[0] <sessionid> <otp>\n";
	exit;
}

print pingid_exec_command('pingid.properties', 'finalizeofflinepairing', array(
		'sessionId' => $argv[1],
		'otp' => $argv[2]
));

?>
