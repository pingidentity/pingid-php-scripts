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

function pingid_add_user($props, $username, $firstname, $lastname, $email) {
	$jwt = pingid_send_request($props, 'adduser/do', array(
		'activateUser' => true,
		'email' => $email,
		'fName' => $firstname,
		'lname' => $lastname,
		'userName' => $username,
		'role' => 'REGULAR',
		'clientData' => null			
	));	
	list($headb64, $bodyb64, $cryptob64) = explode('.', $jwt);
	return pingid_base64url_decode($bodyb64);
}

if (count($argv) < 5) {
	echo "Usage: $argv[0] <username> <first-name> <last-name> <e-mail>\n";
	exit;
}

$props = parse_ini_file('pingid.properties', false, INI_SCANNER_RAW);

$response = pingid_add_user($props, $argv[1], $argv[2], $argv[3], $argv[4]);
print $response;

$json = json_decode($response);
print "\n\n # Activation code is: " . $json->responseBody->activationCode . "\n";
print " # QR Code URL is: " . $props['admin_url'] . '/QRRedirection?' . base64_encode('act_code=' . $json->responseBody->activationCode) . "\n";

?>
