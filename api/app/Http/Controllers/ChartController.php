<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
    public function lihatChart(Request $request)
    {
        return $this->getPaginate(Chart::where('costumer_id', $request->costumer_id)
        ->join('produks', 'produks.id', '=', 'charts.produk_id')
        ->select(DB::raw('charts.*, produks.*'))
        , $request, ['produks.nama']);
    }

    public function tambahChart(Request $request)
    {
        $input = $request->only([
            'costumer_id', 'produk_id'
        ]);
        $validator = Validator::make($input, [
            'costumer_id' => 'required|numeric',
            'produk_id' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        try {
            Chart::create($input);
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp();
    }

    public function ubahChart(Request $request, $id)
    {
        $table = Chart::find($id);
        if (!$table) {
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }
        $input = $request->only([
            'costumer_id', 'produk_id'
        ]);
        $validator = Validator::make($input, [
            'costumer_id' => 'required|numeric',
            'produk_id' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        try {
            $table->update($input);
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp();
    }

    public function delete($id)
    {
        try {
            Chart::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp();
    }
}
