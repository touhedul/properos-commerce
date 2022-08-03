<?php

return [
	'store' => [
		'hiketron' => [
			'seller_id' => env('AMAZON_SELLER_ID'),
			'marketplace_id' => env('ATVPDKIKX0DER'),
			'aws_key_id' =>  env('AMAZON_KEY_ID'),
			'secret_key' => env('AMAZON_SECRET_KEY'),
			'url' => env('AMAZON_AWS_URL', 'https://mws-eu.amazonservices.com/'),
			'mws_auth_token' => env('AMAZON_AUTH_TOKEN'),
			'disable_ssl' => env('AMAZON_DISABLE_SSL', true),
		]
	],
	// Default service URL
	'AMAZON_SERVICE_URL' => 'https://mws.amazonservices.com/',
	'muteLog' => false
];
