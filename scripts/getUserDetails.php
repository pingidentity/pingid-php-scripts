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

function pingid_base64url_encode($input) {
	return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}

function pingid_jwt_encode($payload, $key, $org_alias, $token) {
	$header = array(
			'alg' => 'HS256',
			'orgAlias' => $org_alias,
			'token' => $token
	);
	$segments = array();
	$segments[] = pingid_base64url_encode(json_encode($header));
	$segments[] = pingid_base64url_encode(json_encode($payload));
	$segments[] = pingid_base64url_encode(hash_hmac('SHA256', implode('.', $segments), $key, true));
	return implode('.', $segments);
}

function pingid_get_timestamp() {
	$MDT = 3600 * -6;
	return gmdate("Y-m-d H:i:s.000", time() + $MDT);
}

function pingid_send_request($props, $path, $body) {
	$jwt = array(
		'reqHeader' => array(
			'orgAlias' => $props['org_alias'],
			'secretKey' => $props['token'],
			'timestamp' => pingid_get_timestamp(),
			'version' => '4.6',
			'locale' => 'en',
			'sessionId' => null
		),
		'reqBody' => $body
	);

	$data = pingid_jwt_encode(
			$jwt,
			base64_decode($props['use_base64_key']),
			$props['org_alias'],
			$props['token']);

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$ch = curl_init($props['api-base-url'] . $path);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

function pingid_get_user_details($props, $username) {
	return pingid_send_request($props, 'getuserdetails/do', array(
		'getSameDeviceUsers' => true,
		'userName' => $username,
		'clientData' => null
	));	
}

if (count($argv) < 2) {
	echo "Usage: $argv[0] <username>\n";
	exit;
}

$props = parse_ini_file('pingid.properties', false, INI_SCANNER_RAW);
$props['api-base-url'] = 'https://idpxnyl3m.pingidentity.com/pingid/rest/4/';

print pingid_get_user_details($props, $argv[1]);

?>
