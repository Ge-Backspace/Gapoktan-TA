<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    public function lihatOrder(Request $request)
    {
        return $this->getPaginate(Order::where('costumer_id', $request->costumer_id)
        ->join('addresses', 'addresses.id', '=', 'orders.address_id'),
        $request, ['orders.kd_order']);
    }

    public function tambahOrder(Request $request)
    {
        $input = $request->only([
            'costumer_id',
            'kd_order',
            'address_id',
            'total_harga',
            'deskripsi',
            'no_rek',
            'orders'
        ]);
        $validator = Validator::make($input, [
            'costumer_id' => 'required|numeric',
            'kd_order' => 'required|string',
            'address_id' => 'required',
            'total_harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'no_rek' => 'required|string',
            'orders' => 'required',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $order = Order::create($input);
        $inputOrderDetail = [];
        foreach ($request->orders as $key => $o) {
            $input[] = [
                'produk_id' => $o->produk_id,
                'order_id'=> $order->id,
                'jumlah' => $o->jumlah,
                'harga' => $o->harga
            ];
        }
        OrderDetail::insert($inputOrderDetail);
        return $this->resp($order);
    }

    public function ubahOrder(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'costumer_id',
            'kd_order',
            'address_id',
            'total_harga',
            'status_payment',
            'deskripsi',
            'tanggal_bayar',
            'no_rek',
            'bukti_pembayaran'
        ]);
        $validator = Validator::make($input, [
            'costumer_id' => 'required|numeric',
            'kd_order' => 'required|string',
            'address_id' => 'required',
            'total_harga' => 'required|numeric',
            'status_payment' => 'required|boolean',
            'deskripsi' => 'required|string',
            'no_rek' => 'required|string',
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'required|mimes:jpeg,png,jpg|max:2048',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $file = $request->bukti_pembayaran;
        if ($file) {
            $basePath = base_path('storage/app/public/' . Variable::BPYR);
            $extension = $file->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $file->clientExtension();
            }
            $fileName = Variable::BPYR . '-' . time() . '.' . $extension;
            $file->move($basePath, $fileName);
        }
        $order->update([
            'costumer_id' => $input['costumer_id'],
            'kd_order' => $input['kd_order'],
            'address_id' => $input['address_id'],
            'total_harga' => $input['total_harga'],
            'status_payment' => $input['status_payment'],
            'deskripsi' => $input['deskripsi'],
            'no_rek' => $input['no_rek'],
            'tanggal_bayar' => $input['tanggal_bayar'],
            'bukti_pembayaran' => $file ? $fileName : null,
        ]);
    }

    public function hapusOrder($id)
    {
        try {
            Order::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
