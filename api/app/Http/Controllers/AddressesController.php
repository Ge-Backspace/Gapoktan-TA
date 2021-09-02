<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Address;
use App\Models\Costumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressesController extends Controller
{
    public function lihatAddress(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $costumer = Costumer::where('user_id', $request->user_id)->first();
            if(!$costumer) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $data = Address::where('costumer_id', $state ? $costumer->id : $request->costumer_id)
        ->orderBy('id', 'DESC');
        return $this->getPaginate($data, $request, ['nama']);
    }

    public function tambahAddress(Request $request)
    {
        $input = $request->only([
            'nama', 'nomor_hp', 'provinsi_id', 'provinsi', 'type', 'kota_id', 'kota', 'postal_code', 'alamat'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'nomor_hp' => 'required|numeric',
            'provinsi_id' => 'required|numeric',
            'provinsi' => 'required|string',
            'type' => 'required|string',
            'kota_id' => 'required|numeric',
            'kota' => 'required|string',
            'postal_code' => 'required|string',
            'alamat' => 'required',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        if ($request->user_id) {
            $costumer = Costumer::where('user_id', $request->user_id)->first();
            if(!$costumer) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
        }
        $add = Address::create([
            'nama' => $input['nama'],
            'nomor_hp' => $input['nomor_hp'],
            'provinsi_id' => $input['provinsi_id'],
            'provinsi' => ucfirst(trans($input['provinsi'])),
            'type' => ucfirst($input['type']),
            'kota_id' => $input['kota_id'],
            'kota' => ucfirst(trans($input['kota'])),
            'postal_code' => $input['postal_code'],
            'alamat' => $input['alamat'],
            'costumer_id' => $costumer->id
        ]);
        return $this->resp($add);
    }

    public function ubahAddress(Request $request, $id)
    {
        $data = Address::find($id);
        if (!$data) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only(['nama', 'nomor_hp', 'alamat']);
        $validator = Validator::make($input, [
            'nama' => 'required',
            'nomor_hp' => 'required',
            'alamat' => 'required',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $inputUpdate = [
            'nama' => $input['nama'],
            'nomor_hp' => $input['nomor_hp'],
            'alamat' => $input['alamat'],
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusAddress($id)
    {
        try {
            Address::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
