<?php 

namespace Hsubuu\Merchant\Controllers;



use Eljam\GuzzleJwt\JwtMiddleware;
use Eljam\GuzzleJwt\Manager\JwtManager;
// use Eljam\GuzzleJwt\Strategy\Auth\QueryAuthStrategy;
use Eljam\GuzzleJwt\Strategy\Auth\JsonAuthStrategy;

use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Notifications\NotificationServiceProvider;
use App\Merchant;

class MerchantController extends Controller
{
	private $http;
	private $token;
	public $username;
	public $password;
	public function __construct(Merchant $merchant)
	{
		$this->http =  new \GuzzleHttp\Client(['headers' => array('X-API-KEY' => config('app.hsubuu_api_key'),'X-API-SECRET' => config('app.hsubuu_api_secret'), 'Content-Type'   =>  'application/x-www-form-urlencoded')]);
		$this->token = \Cache::get('token');
		$this->username  = $merchant->username;
		$this->password  = $merchant->password;
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
			$this->username  = $request->input('username');
			$this->password  = $request->input('password');
			return response_ok(['message' => 'login success']);
		}
		catch (\GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}
		$authStrategy = new JsonAuthStrategy(['username' => $request->input('username'), 'password' => $request->input('password')]);
		$baseUri = 'http://localhost/HsuBuu(MerchantApi)/public/api/v1/merchant';
		$authClient = new \GuzzleHttp\Client(['base_uri' => $baseUri]);
		// $jwtManager = new JwtManager(
		// 	$authClient,
		// 	$authStrategy,
		// 	[
		// 		'token_url' => '/login',
		// 	]
		// );
		$stack = HandlerStack::create();
		$stack->push(new JwtMiddleware($response_token));
		$client = new \GuzzleHttp\Client(['handler' => $stack, 'base_uri' => $baseUri]);
		try {
			$response = $client->get('/');
			echo($response->getBody());
		} catch (TransferException $e) {
			echo $e->getMessage();
		}
	}

	public function merchant()
	{
		dd($this->username);
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