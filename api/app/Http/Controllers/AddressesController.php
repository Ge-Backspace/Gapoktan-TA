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
            'nama', 'nomor_hp', 'alamat', 'costumer_id'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'costumer_id' => 'numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $costumer = Costumer::where('user_id', $request->user_id)->first();
            if(!$costumer) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $add = Address::create([
            'nama' => $input['nama'],
            'nomor_hp' => $input['nomor_hp'],
            'alamat' => $input['alamat'],
            'costumer_id' => $state ? $costumer->id : $input['costumer_id']
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
