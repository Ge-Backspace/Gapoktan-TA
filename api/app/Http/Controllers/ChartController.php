<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Chart;
use App\Models\Costumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
    public function lihatChart(Request $request)
    {
        $input = $request->only([
            'costumer_id'
        ]);
        $validator = Validator::make($input, [
            'costumer_id' => 'numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $costumer = Costumer::where('user_id', $request->user_id)->first();
            $state = true;
        }
        return $this->getPaginate(Chart::where('costumer_id', $state ? $costumer->id : $request->costumer_id)
        ->join('produks', 'produks.id', '=', 'charts.produk_id')
        ->leftJoin('thubnail_produks', 'thubnail_produks.produk_id', '=', 'produks.id')
        ->where('charts.status', false)
        ->select(DB::raw('charts.*, produks.*, produks.id as produk_id,charts.id as id, thubnail_produks.nama as nama_thumbnail'))
        ->orderBy('charts.id', 'DESC')
        , $request, ['produks.nama']);
    }

    public function tambahChart(Request $request)
    {
        $input = $request->only([
            'costumer_id', 'produk_id', 'jumlah'
        ]);
        $validator = Validator::make($input, [
            'costumer_id' => 'numeric',
            'produk_id' => 'required|numeric',
            'jumlah' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $costumer = Costumer::where('user_id', $request->user_id)->first();
            $state = true;
        }
        try {
            $chart = Chart::create([
                'produk_id' => $input['produk_id'],
                'costumer_id' => $state ? $costumer->id : $input['costumer_id'],
                'jumlah' => $input['jumlah']
            ]);
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp($chart);
    }

    public function ubahJumlahChart(Request $request, $id)
    {
        $input = $request->only([
            'jumlah'
        ]);
        $validator = Validator::make($input, [
            'jumlah' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        try {
            Chart::find($id)->update(['jumlah' => $input['jumlah']]);
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp();
    }

    public function hapusChart($id)
    {
        try {
            Chart::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp();
    }
}
