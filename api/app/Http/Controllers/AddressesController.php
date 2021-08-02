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
        $input = $request->only([
            'costumer_id'
        ]);
        $validator = Validator::make($input, [
            'costumer_id' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $data = Address::where('costumer_id', $input['costumer_id'])
        ->orderBy('id', 'DESC');
        return $this->getPaginate($data, $request, []);
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
            'costumer_id' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $costumer = Costumer::find($request->costumer_id);
        if (!$costumer) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        } else {
            $add = Address::create($input);
            return $this->resp($add);
        }
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
        $data = Address::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
