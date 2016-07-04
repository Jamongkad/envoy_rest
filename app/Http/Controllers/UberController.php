<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UberController extends Controller
{

    public function __construct(Request $request) {
        $this->bearerToken = $request->session()->get('uber.token');
        $this->refreshToken = $request->session()->get('uber.refresh_token');

        $this->prod_url = 'https://api.uber.com/';
        $this->sandbox_url = 'https://sandbox-api.uber.com/';
    }

    public function get_products(Request $request) { 

        $latitude  = $request->input('latitude');
        $longitude =  $request->input('longitude');

        $curl = new \Curl\Curl();
        $curl->get($this->sandbox_url.'v1/products', [
           'server_token' => env('UBER_SERVER_TOKEN'),
           'latitude' => $latitude,
           'longitude' => $longitude
        ]);

        $data = json_decode($curl->response);
        return \Response::json($data);
    }

    public function make_request(Request $request) {
        $curl = new \Curl\Curl();
        $curl->setHeader('Authorization', 'Bearer ' . $this->bearerToken);
        $curl->setHeader('Content-Type', 'application/json');

        $params = [ 
            'product_id' => 'feab4fca-ea1b-4c3d-b557-9add0f486c73',
            'start_latitude' => '14.557112',
            'start_longitude'  => '121.018889',
            'end_latitude' => '14.558607',
            'end_longitude' => '121.027386',
        ];

        $curl->post($this->sandbox_url.'v1/requests', json_encode($params));

        $data = json_decode($curl->response);

        dd($data);
    }
}
