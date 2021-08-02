<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    public function lihatOrderDetail(Request $request)
    {
        return $this->getPaginate(
            OrderDetail::where('order_id', $request->order_id)
            ->join('produks', 'produks.id', '=', 'order_details.produk_id')
            ->orderBy('order_details.id', 'DESC'),
            $request, ['produks.nama']
        );
    }

    public function ubahOrderDetail(Request $request, $id)
    {
        $table = OrderDetail::find($id);
        if (!$table) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'produk_id', 'order_id', 'jumlah', 'harga'
        ]);
        $validator = Validator::make($input, [
            'produk_id' => 'required|numeric',
            'order_id' => 'required|numeric',
            'jumlah_id' => 'required|numeric',
            'harga' => 'required|numeric',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $table->update([
            'produk_id' => $input['produk_id'],
            'order_id' => $input['order_id'],
            'jumlah' => $input['jumlah'],
            'harga' => $input['harga']
        ]);
    }

    public function hapusOrderDetail($id)
    {
        try {
            OrderDetail::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
