# PHP API Client for [VR Cleaning](https://www.vrcleaning.net)

An easy to use PHP Client, the only extensions you need in your PHP are cURL and JSON.

You can find our full API documentation at https://www.vrcleaning.net/apidoc

Example usage: (more in example.php)
```
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
```
