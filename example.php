<?php
/******************************************************************************
Example for the PHP API client for VR Cleaning, https://www.vrcleaning.net
Licensed under the BSD 3-Clause License, see included LICENSE file for details.
Copyright (c) 2023 VR Cleaning, LLC. All Rights Reserved.
******************************************************************************/

require('vrcleaning.api.client.php');

$c = new VRCleaningAPI('pub_...', 'priv_...');

/* Example GET request */

$reply = $c->get('business/permissions');
if ($reply['success']) {
	print_r($reply);
} else {
	die("Error getting business permissions: ".$reply['error']."\n");
}

/* Example POST request creating a business */

$reply = $c->post('business', ['name' => 'API Test']);
if ($reply['success']) {
	print "Business created!\n";
	print_r($reply);
} else {
	die("Error creating business: ".$reply['error']."\n");
}
