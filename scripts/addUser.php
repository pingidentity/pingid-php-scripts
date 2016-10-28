<?php
/*
	PingID API Sample PHP Script: addUser.php
	
	This script will add a PingID user via the PingID API AddUser operation.
	For more information, review the API documentation:
	https://developer.pingidentity.com/en/api/pingid-api.html

	Note:	This software is open sourced by Ping Identity but not supported commercially
			as such. Any questions/issues should go to the Github issues tracker or discuss
			on the [Ping Identity developer communities] . See also the DISCLAIMER file in
			this directory.
*/

require_once 'Utils.php';

if (count($argv) < 5) {
	echo "Usage: $argv[0] <username> <first-name> <last-name> <e-mail> [true|false]\n";
	exit;
}

$props_file = 'pingid.properties';

$response = pingid_exec_command($props_file, 'adduser', array(
		'activateUser' => count($argv) > 5 ? $argv[5] == "true" : "true",
		'email' => $argv[4],
		'fName' => $argv[2],
		'lname' => $argv[3],
		'userName' => $argv[1],
		'role' => 'REGULAR'
));
print $response;

$props = parse_ini_file($props_file, false, INI_SCANNER_RAW);

$json = json_decode($response);
print "\n\n # Activation code is: " . $json->responseBody->activationCode . "\n";
print " # QR Code URL is: " . $props['admin_url'] . '/QRRedirection?' . base64_encode('act_code=' . $json->responseBody->activationCode) . "\n";

?>
