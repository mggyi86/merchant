<?php 

namespace Hsubuu\Merchant\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class MerchantController extends Controller
{
	private $http;
	private $token;
	public function __construct()
	{
		$this->http =  new \GuzzleHttp\Client(['headers' => array('X-API-KEY' => config('app.hsubuu_api_key'),'X-API-SECRET' => config('app.hsubuu_api_secret'), 'Content-Type'   =>  'application/x-www-form-urlencoded')]);
		$this->token = \Cache::get('token');
	}

	public function login(Request $request)
	{
		try{
			$attempt = $this->http->post(config('app.hsubuu_api_url').'/login',[
				'form_params' => $request->all()
			]);
			$response = json_decode($attempt->getBody(), true);
			$response_token = $response['token'];
			$expiresAt = \Carbon::now()->addMinutes(10);
			\Cache::put('token', $response_token , $expiresAt);
			return response_ok(['message' => 'login success']);
		}
		catch (\GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}
	}

	public function merchant()
	{
		try{
			$token = \Cache::get('token');
			$response = $this->http->get(config('app.hsubuu_api_url'), [ 'headers' => ['Authorization' => $this->token] ]);
			$attempt_json  = json_decode($response->getBody());
			return response_ok($attempt_json);
		}
		catch (\GuzzleHttp\Exception\ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}

	}
	
	public function change_password(Request $request)
	{
		try{
			$response = $this->http->put(config('app.hsubuu_api_url').'/change_password', [ 'headers' => ['Authorization' => $this->token],  'json' => ['old_password' => $request->input('old_password'), 'new_password' => $request->input('new_password')] ]);
			$attempt_json  = json_decode($response->getBody(), true);
			return response_ok($attempt_json);
		}
		catch (\GuzzleHttp\Exception\ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}
	}

	public function give_code(Request $request)
	{
		try{
			$response = $this->http->post(config('app.hsubuu_api_url').'/give_code', [ 'headers' => ['Authorization' => $this->token],  'json' => ['amount' => $request->input('amount'), 'message' => $request->input('message')] ]);
			$attempt_json  = json_decode($response->getBody(), true);
			return response_ok($attempt_json);
		}
		catch (\GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse();
    		$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}

	}

	public function used_code(Request $request)
	{
		try{
			$response = $this->http->get(config('app.hsubuu_api_url').'/used_code', [ 'headers' => ['Authorization' => $this->token] ]);
			$attempt_json  = json_decode($response->getBody(), true);
			return response_ok($attempt_json);
		}
		catch (\GuzzleHttp\Exception\ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}
	}

	public function exchange_gift(Request $request)
	{
		try{
			$response = $this->http->get(config('app.hsubuu_api_url').'/exchange_gift', [ 'headers' => ['Authorization' => $this->token] ]);
			$attempt_json  = json_decode($response->getBody(), true);
			return response_ok($attempt_json);
		}
		catch (\GuzzleHttp\Exception\ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}

	}

}