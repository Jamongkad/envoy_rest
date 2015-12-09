<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Curl\Curl;
use urmaul\url\Url;

class AuthController extends Controller
{
    //
    public function login(Request $request) {
        $provider = new \Stevenmaguire\OAuth2\Client\Provider\Uber([
            'clientId'          => env('UBER_CLIENT_ID'),
            'clientSecret'      => env('UBER_CLIENT_SECRET'),
            'redirectUri'       => env('UBER_REDIRECT_URI')
        ]);

        if (!isset($_GET['code'])) {
             

            $authorizationUrl = "https://login.uber.com/oauth/v2/authorize";
            $params = [ 
                'response_type' => 'code',
                'client_id' => env('UBER_CLIENT_ID'),
                'scope' => 'profile request request_receipt delivery_sandbox history',
                'redirect_uri' => env('UBER_REDIRECT_URI')
            ];

            $url = Url::from($authorizationUrl)->addParams($params);
            header('Location: '.$url);
            exit;

        // Check given state against previously stored one to mitigate CSRF attack
        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);

                // Use these details to create a new profile
                $request->session()->put('uber.token', $token->getToken());
                $request->session()->put('uber.refresh_token', $token->getRefreshToken());

            } catch (Exception $e) {

                // Failed to get user details
                exit('Oh dear...');
            }

            // Use this to interact with an API on the users behalf
            echo $token->getToken();

        }
 
    }
    
    public function test_token(Request $request) {

        $bearerToken = $request->session()->get('uber.token');
        $refreshToken = $request->session()->get('uber.refresh_token');
        
        echo json_encode(['bearerToken' => $bearerToken, 'refreshToken' => $refreshToken]);
    }
}
