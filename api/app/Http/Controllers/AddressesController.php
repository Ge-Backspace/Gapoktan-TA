<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Address;
use App\Models\Costumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressesController extends Controller
{
    public function lihatAddress(Request $request)
    {
        $data = Address::where('costumer_id', $request->costumer_id)
        ->join('costomers', 'costomer.id', '=', 'Addresses.costomer_id')
        ->select(DB::raw('costomers.*, costomers.nama as nama'));
        return $this->getPaginate($data, $request, ['adresses.nama', 'adresses.nomor_hp', 'addresses.alamat']);
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
            'costumer_id' => 'required|numeric'
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
