<?php
/******************************************************************************
PHP API client for VR Cleaning, https://www.vrcleaning.net
Licensed under the BSD 3-Clause License, see included LICENSE file for details.
Copyright (c) 2023 VR Cleaning, LLC. All Rights Reserved.
******************************************************************************/

if (!function_exists('curl_init')) {
	die('The VR Cleaning API client needs the PHP curl extension loaded!');
}

class VRCleaningAPI {
	private $url = 'https://www.vrcleaning.net';
	private $root = '/api/v1/';
	private $key = '';
	private $secret = '';
	private $ch_get = FALSE;
	private $ch_delete = FALSE;
	private $ch_post = FALSE;
	private $ch_put = FALSE;

	function __construct($key, $secret) {
		$this->key = $key;
		$this->secret = $secret;

		$comopts = array(
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FAILONERROR => FALSE,
			CURLOPT_USERAGENT => 'VRCleaningAPI/PHP/1.0',
		);
		$this->ch_get = curl_init();
		if ($this->ch_get !== FALSE) {
			curl_setopt_array($this->ch_get, $comopts);
		}
		$this->ch_delete = curl_init();
		if ($this->ch_delete !== FALSE) {
			$comopts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
			curl_setopt_array($this->ch_delete, $comopts);
			unset($comopts[CURLOPT_CUSTOMREQUEST]);
		}
		$this->ch_post = curl_init();
		if ($this->ch_post !== FALSE) {
			$comopts[CURLOPT_POST] = TRUE;
			curl_setopt_array($this->ch_post, $comopts);
		}
		$this->ch_put = curl_init();
		if ($this->ch_put !== FALSE) {
			$comopts[CURLOPT_CUSTOMREQUEST] = 'PUT';
			curl_setopt_array($this->ch_put, $comopts);
		}
	}

	function get($path) {
		if (empty($path)) {
			return array('error' => 'No path specified!');
		}
		if ($this->ch_get === FALSE) {
			return array('error' => 'Error creating cURL handle!');
		}
		if (empty($this->key) || empty($this->secret)) {
			return array('error' => 'No API key and/or secret passed!');
		}

		$url = $this->root . $path;

		// build data to HMAC, the user's API key + method + URL path concatenated
		$data = $this->key . 'GET' . $url;
		// HMAC the data using the user's API secret
		$hmac = hash_hmac('sha512', $data, $this->secret);

		$headers = array(
			'API-KEY: '.$this->key,
			'HMAC: '.$hmac,
		);

		curl_setopt_array($this->ch_get, array(
			CURLOPT_URL => $this->url . $url,
			CURLOPT_HTTPHEADER => $headers,
		));

		$ret = curl_exec($this->ch_get);
		if ($ret !== FALSE) {
			$ret = json_decode($ret, TRUE);
			if (is_array($ret)) {
				return $ret;
			} else {
				return array('error' => 'Error decoding response!');
			}
		} else {
			return array('error' => 'Error executing cURL handle: ' . curl_error($this->ch_get));
		}
	}

	function delete($path) {
		if (empty($path)) {
			return array('error' => 'No path specified!');
		}
		if ($this->ch_delete === FALSE) {
			return array('error' => 'Error creating cURL handle!');
		}
		if (empty($this->key) || empty($this->secret)) {
			return array('error' => 'No API key and/or secret passed!');
		}

		$url = $this->root . $path;

		// build data to HMAC, the user's API key + method + URL path concatenated
		$data = $this->key . 'DELETE' . $url;
		// HMAC the data using the user's API secret
		$hmac = hash_hmac('sha512', $data, $this->secret);

		$headers = array(
			'API-KEY: '.$this->key,
			'HMAC: '.$hmac,
		);

		curl_setopt_array($this->ch_delete, array(
			CURLOPT_URL => $this->url . $url,
			CURLOPT_HTTPHEADER => $headers,
		));

		$ret = curl_exec($this->ch_delete);
		if ($ret !== FALSE) {
			$ret = json_decode($ret, TRUE);
			if (is_array($ret)) {
				return $ret;
			} else {
				return array('error' => 'Error decoding response!');
			}
		} else {
			return array('error' => 'Error executing cURL handle: ' . curl_error($this->ch_delete));
		}
	}

	function post($path, $params = array()) {
		if (empty($path)) {
			return array('error' => 'No path specified!');
		}
		if (!is_array($params)) {
			return array('error' => 'Params must be an array!');
		}
		if ($this->ch_post === FALSE) {
			return array('error' => 'Error creating cURL handle!');
		}
		if (empty($this->key) || empty($this->secret)) {
			return array('error' => 'No API key and/or secret passed!');
		}

		ksort($params); //sort keys alphabetically
		$post_data = http_build_query($params);
		$url = $this->root . $path;

		// build data to HMAC, the user's API key + method + URL path + raw POST string concatenated
		$data = $this->key . 'POST' . $url . $post_data;
		// HMAC the data using the user's API secret
		$hmac = hash_hmac('sha512', $data, $this->secret);

		$headers = array(
			'Content-Type: application/x-www-form-urlencoded',
			'API-KEY: '.$this->key,
			'HMAC: '.$hmac,
		);

		curl_setopt_array($this->ch_post, array(
			CURLOPT_URL => $this->url . $url,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $post_data,
		));

		$ret = curl_exec($this->ch_post);
		if ($ret !== FALSE) {
			$ret = json_decode($ret, TRUE);
			if (is_array($ret)) {
				return $ret;
			} else {
				return array('error' => 'Error decoding response!');
			}
		} else {
			return array('error' => 'Error executing cURL handle: ' . curl_error($this->ch_post));
		}
	}

	function put($path, $params = array()) {
		if (empty($path)) {
			return array('error' => 'No path specified!');
		}
		if (!is_array($params)) {
			return array('error' => 'Params must be an array!');
		}
		if ($this->ch_put === FALSE) {
			return array('error' => 'Error creating cURL handle!');
		}
		if (empty($this->key) || empty($this->secret)) {
			return array('error' => 'No API key and/or secret passed!');
		}

		ksort($params); //sort keys alphabetically
		$post_data = http_build_query($params);
		$url = $this->root . $path;

		// build data to HMAC, the user's API key + method + URL path + raw POST string concatenated
		$data = $this->key . 'PUT' . $url . $post_data;
		// HMAC the data using the user's API secret
		$hmac = hash_hmac('sha512', $data, $this->secret);

		$headers = array(
			'Content-Type: application/x-www-form-urlencoded',
			'API-KEY: '.$this->key,
			'HMAC: '.$hmac,
		);

		curl_setopt_array($this->ch_put, array(
			CURLOPT_URL => $this->url . $url,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $post_data,
		));

		$ret = curl_exec($this->ch_put);
		if ($ret !== FALSE) {
			$ret = json_decode($ret, TRUE);
			if (is_array($ret)) {
				return $ret;
			} else {
				return array('error' => 'Error decoding response!');
			}
		} else {
			return array('error' => 'Error executing cURL handle: ' . curl_error($this->ch_put));
		}
	}
}
