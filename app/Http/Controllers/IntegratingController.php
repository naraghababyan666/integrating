<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IntegratingController extends Controller
{
    public function auth(){
        $http= new \GuzzleHttp\Client();
        $response = $http->request('GET','https://mixpanel.com/api/app/me', [
            'headers' => [
                'cache-control' => 'no-cache',
//                'Content-Type' => 'application/x-www-form-urlencoded',
                'authorization' => 'Basic ' . 'test.bb1560.mp-service-account:KkXYmfkFXPRZJaqbzEj5Hoy9tD8jacDB',
            ]
        ]);
        return json_decode((string) $response->getBody(), true);
//        return $this->createEvent();

    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */


    public function createEvent(){
        $http= new \GuzzleHttp\Client();
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 15; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $date = (int) round(now()->format('Uu') / pow(10, 6 - 3));
//        dd('[{"event":"Session recording","properties":{"time":"' . $date . '","distinct_id":"test12tt@mail.ru","$insert_id":"' . $randomString . '"}}]');
        $response = $http->request('POST', 'https://api.mixpanel.com/import?strict=1&project_id=2353140', [
            'body' => '[{"event":"Session Records","properties":{"time":' . '1660032558353' . ',"distinct_id":"test@email.com","$insert_id":"'. $randomString . '", "email" : "test@mail.ru"}}]',
//            'body' => '[{"event":"Session recording","properties":{"time":"' . $date . '","distinct_id":"test12tt@mail.ru","$insert_id":"' . $randomString . '"}}]',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic dGVzdC5iYjE1NjAubXAtc2VydmljZS1hY2NvdW50OktrWFltZmtGWFBSWkphcWJ6RWo1SG95OXREOGphY0RC',
                'Content-Type' => 'application/json',
            ],
        ]);
//        'body' => '[{"event":"123123","properties":{"time":1659512632602,"distinct_id":"91304156-cafc-4673-a237-623d1129c801","$insert_id":"29fc2962-6d9c-455d-95ad-95b84f09b9e4"}}]',
//        dd($response->getBody());
        return json_decode((string) $response->getBody(), true);

    }

    public function getData(){
        $http= new \GuzzleHttp\Client();
        $credentials = base64_encode('michael@getjones.com:c41497251271b785bbc91f08f3a0f9ea9b6a828b');
        $a = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => ['Basic '.$credentials],
            'Content-Type' => 'application/json',
        ])->get('https://api.inspectlet.com/v1/websites');
        $b = (array) json_decode($a->body());
        $wid = $b['response'][0]->wid;

        Http::post('https://api.inspectlet.com/v1/websites/'.$wid.'/sessions');
//        return $this->auth();
    }
}
