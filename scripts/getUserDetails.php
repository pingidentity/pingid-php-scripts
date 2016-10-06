<?php
/*
	PingID API Sample PHP Script: getUserDetails.php
	
	This script will query the PingID API GetUserDetails operation to return the details
	about a PingID user. For more information, review the API documentation:
	https://developer.pingidentity.com/en/api/pingid-api.html

	Note:	This software is open sourced by Ping Identity but not supported commercially
			as such. Any questions/issues should go to the Github issues tracker or discuss
			on the [Ping Identity developer communities] . See also the DISCLAIMER file in
			this directory.
*/

require_once 'Utils.php';

function pingid_get_user_details($props, $username) {
	$jwt = pingid_send_request($props, 'getuserdetails/do', array(
		'getSameDeviceUsers' => true,
		'userName' => $username,
		'clientData' => null
	));	
	list($headb64, $bodyb64, $cryptob64) = explode('.', $jwt);
	return pingid_base64url_decode($bodyb64);
}

if (count($argv) < 2) {
	echo "Usage: $argv[0] <username>\n";
	exit;
}

$props = parse_ini_file('pingid.properties', false, INI_SCANNER_RAW);

print pingid_get_user_details($props, $argv[1]);

?>
