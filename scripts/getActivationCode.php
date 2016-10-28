<?php
/*
	PingID API Sample PHP Script: getActivationCode.php
	
	This script will call the PingID API GetActivationCode operation to get an activation code for
	a PingID user. For more information, review the API documentation:
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

$props_file = 'pingid.properties';

$response = pingid_exec_command($props_file, 'getactivationcode', array(
		'userName' => $argv[1]
));
print $response;

$props = parse_ini_file($props_file, false, INI_SCANNER_RAW);

$json = json_decode($response);
print "\n\n # Activation code is: " . $json->responseBody->activationCode . "\n";
print " # QR Code URL is: " . $props['admin_url'] . '/QRRedirection?' . base64_encode('act_code=' . $json->responseBody->activationCode) . "\n";

?>
