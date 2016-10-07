<?php
/*
	PingID API Sample PHP Script: Utils.php
	
	This script provided common routines for accessing the PingID API.
	For more information, review the API documentation:
	https://developer.pingidentity.com/en/api/pingid-api.html

	Note:	This software is open sourced by Ping Identity but not supported commercially
			as such. Any questions/issues should go to the Github issues tracker or discuss
			on the [Ping Identity developer communities] . See also the DISCLAIMER file in
			this directory.
*/
define('PINGID_API_VERSION', '4.9');

function pingid_base64url_encode($input) {
	return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}

function pingid_base64url_decode($input) {
	$remainder = strlen($input) % 4;
	if ($remainder) {
		$padlen = 4 - $remainder;
		$input .= str_repeat('=', $padlen);
	}
	return base64_decode(strtr($input, '-_', '+/'));
}

function pingid_jwt_encode($payload, $key, $org_alias, $token) {
	$header = array(
			'alg' => 'HS256',
			'org_alias' => $org_alias,
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
			'version' => PINGID_API_VERSION,
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

	$api_url = $props['admin_url'] . '/rest/4/' . $path;
	
	#echo 
	#print_r($data);
	#echo
	
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$ch = curl_init($api_url);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

function pingid_exec_command($prop_file, $cmd_path, $req_array) {
	$props = parse_ini_file($prop_file, false, INI_SCANNER_RAW);
	$req_array['clientData'] = null;
	$jwt = pingid_send_request($props, $cmd_path . '/do', $req_array);
	list($headb64, $bodyb64, $cryptob64) = explode('.', $jwt);
	return pingid_base64url_decode($bodyb64);
}

?>
