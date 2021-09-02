<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Costumer;
use App\Models\Kategori;
use App\Models\Poktan;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{
    public function optionPoktan()
    {
        return $this->resp(Poktan::all());
    }

    public function optionKategori()
    {
        return $this->resp(Kategori::all());
    }

    public function optionAddress(Request $request)
    {
        $costumer = Costumer::where('user_id', $request->user_id)->first();
        return $this->resp(Address::where('costumer_id', $costumer->id)->get());
    }

    public function getOngkir(Request $request)
    {
        $input = $request->only([
            'origin', 'weight', 'courier', 'destination'
        ]);
        $validator = Validator::make($input, [
            'origin' => 'required|numeric',
            'weight' => 'required|numeric',
            'destination' => 'required|numeric',
            'courier' => 'required|string',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        try {
            $client = new Client();
            $response = $client->request('POST', 'https://api.rajaongkir.com/starter/cost', [
                'headers' => [
                    'key' => '9779258e75e26b351caf104847a81d0f',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'origin' => $input['origin'],
                    'destination' => $input['destination'],
                    'weight' => $input['weight'],
                    'courier' => $input['courier'],
                ]
            ]);
            // $contents = $response->getBody()->getContents();
            $contents = json_decode($response->getBody(), true);
            return $this->resp($contents);
        } catch (\Throwable $th) {
            return $this->resp(null, $th->getMessage(), false, 400);
        }
    }

    public function getProvinsi()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://api.rajaongkir.com/starter/province', [
                'headers' => [
                    'key' => '9779258e75e26b351caf104847a81d0f',
                ],
            ]);
            $contents = json_decode($response->getBody(), true);
            return $this->resp($contents);
        } catch (\Throwable $th) {
            return $this->resp(null, $th->getMessage(), false, 400);
        }
    }

    public function getKota($id)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://api.rajaongkir.com/starter/city?province='.$id, [
                'headers' => [
                    'key' => '9779258e75e26b351caf104847a81d0f',
                ],
            ]);
            $contents = json_decode($response->getBody(), true);
            return $this->resp($contents);
        } catch (\Throwable $th) {
            return $this->resp(null, $th->getMessage(), false, 400);
        }
    }
}
