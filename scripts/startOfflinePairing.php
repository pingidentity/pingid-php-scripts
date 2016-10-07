<?php
/*
	PingID API Sample PHP Script: startOfflinePairing.php
	
	This script will initiate offline pairing via the PingID API StartOfflinePairing operation.
	For more information, review the API documentation:
	https://developer.pingidentity.com/en/api/pingid-api.html

	Note:	This software is open sourced by Ping Identity but not supported commercially
			as such. Any questions/issues should go to the Github issues tracker or discuss
			on the [Ping Identity developer communities] . See also the DISCLAIMER file in
			this directory.
*/

require_once 'Utils.php';

if (count($argv) < 4) {
	echo "Usage: $argv[0] <username> [SMS <phone-number> | VOICE <phone-number> | EMAIL <email-address>]\n";
	exit;
}

$response = pingid_exec_command('pingid.properties', 'startofflinepairing', array(
		'username' => $argv[1],
		'type' => $argv[2],
		'pairingData' => $argv[3]
));
print $response;

$json = json_decode($response);
print "\n\n # sessionId is: " . $json->responseBody->sessionId . "\n";

?>
