<?php

namespace Properos\Commerce\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Properos\Base\Classes\Api;

class ApiLocationController extends Controller
{

	function __construct()
	{
		//	
	}

	public function getCountries()
	{
		$json = file_get_contents(env('APP_URL') .'/json/countries.json');
		if ($json != false) {
			return Api::success('Countries loaded successfully',json_decode($json));
		}
		return Api::error('001', 'Error loading countries data', []);
	}

	public function getStates()
	{
		$json = file_get_contents(env('APP_URL') .'/json/us-states.json');
		if ($json != false) {
			return Api::success('States loaded successfully',json_decode($json));
		}
		return Api::error('001', 'Error loading state data', []);
	}
}