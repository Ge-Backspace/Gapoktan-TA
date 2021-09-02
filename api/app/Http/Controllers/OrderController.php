<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Chart;
use App\Models\Costumer;
use App\Models\Gapoktan;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OrderDetail;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function lihatOrder(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $costumer = Costumer::where('user_id', $request->user_id)->first();
            if(!$costumer) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        return $this->getPaginate(Order::join('addresses', 'addresses.id', '=', 'orders.address_id')
        ->where('orders.costumer_id', $state ? $costumer->id : $request->costumer_id)
        ->select(DB::raw('addresses.*, orders.*, orders.id as id, addresses.nama as nama_alamat'))
        ->orderBy('orders.id', 'DESC'),
        $request, ['orders.kd_order']);
    }

    public function lihatSemuaOrder(Request $request)
    {
        $data = Order::join('addresses', 'addresses.id', '=', 'orders.address_id')
        ->select(DB::raw('orders.*, addresses.*, orders.id as id, addresses.nama as nama_alamat'))
        ->orderBy('orders.id', 'DESC');
        return $this->getPaginate( $data, $request, ['orders.kd_order']);
    }

    public function lihatOrderGapoktan(Request $request)
    {
        $gapoktan = Gapoktan::where('user_id', $request->user_id)->first();
        if (!$gapoktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $data = OrderDetail::join('produks', 'produks.id', '=', 'order_details.produk_id')
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->join('addresses', 'addresses.id', '=', 'orders.address_id')
        ->leftJoin('thubnail_produks', 'thubnail_produks.produk_id', '=', 'produks.id')
        ->where('produks.gapoktan_id', $gapoktan->id)
        ->where('orders.status_order', 3)
        ->orWhere('orders.status_order', 2)
        ->orWhere('orders.status_order', 1)
        ->select(DB::raw('order_details.*, addresses.*, orders.*, produks.*, order_details.id as id, addresses.nama as nama_alamat, thubnail_produks.nama as nama_thumbnail, produks.nama as nama_produk'))
        ->orderBy('order_details.id', 'DESC');
        return $this->getPaginate($data, $request, []);
    }

    public function tambahOrder(Request $request)
    {
        $costumer = Costumer::where('user_id', $request->user_id)->first();
        if ($costumer) {
            $input = $request->only(['address_id', 'total_harga', 'deskripsi', 'cart']);
            $validator = Validator::make($input, [
                'address_id' => 'required|numeric',
                'total_harga' => 'required|numeric',
                'deskripsi' => 'string',
                'cart' => 'required',
            ], Helper::messageValidation());
            if ($validator->fails()) {
                return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
            }
            $kode = rand(100,999);
            $order = Order::create([
                'costumer_id' => $costumer->id,
                'address_id' => $input['address_id'],
                'deskripsi' => $input['deskripsi'],
                'total_harga' => $input['total_harga'],
                'kd_order' => time().'-'.$kode
            ]);
            $inputOD = [];
            foreach ($request->cart as $key => $value) {
                $cart = Chart::find($value);
                $produk = Produk::find($cart->produk_id);
                $inputOD[] = [
                    'produk_id' => $cart->produk_id,
                    'order_id' => $order->id,
                    'jumlah' => $cart->jumlah,
                    'harga' => $produk->harga
                ];
                $cart->update(['status' => true]);
            }
            OrderDetail::insert($inputOD);
            return $this->resp($order);
        }
        return $this->resp(null, Variable::NOT_FOUND, false, 406);
    }

    public function ubahOrder(Request $request, $id)
    {
        if ($request->web) {
            $order = Order::find($id);
            if (!$order) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $input = $request->only([
                'address_id',
                'deskripsi',
                'tanggal_bayar',
                'no_rek',
                'atas_nama',
                'bukti_pembayaran',
                'kurir',
                'service',
                'etd',
                'ongkir'
            ]);
            $validator = Validator::make($input, [
                'address_id' => 'numeric',
                'deskripsi' => 'string',
                'no_rek' => 'required|string',
                'atas_nama' => 'required|string',
                'tanggal_bayar' => 'required|date',
                'kurir' => 'required|string',
                'service' => 'required|string',
                'etd' => 'required|string',
                'ongkir' => 'required|numeric',
                'bukti_pembayaran' => 'required|mimes:jpeg,png,jpg|max:5048',
            ], Helper::messageValidation());
            if ($validator->fails()) {
                return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
            }
            $file = $request->file('bukti_pembayaran');
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
                'address_id' => $request->address_id ? $input['address_id'] : $order->address_id,
                'status_payment' => true,
                'deskripsi' => $request->deskripsi ? $input['deskripsi'] : $order->deskripsi,
                'no_rek' => $input['no_rek'],
                'atas_nama' => $input['atas_nama'],
                'tanggal_bayar' => $input['tanggal_bayar'],
                'kurir' => strtoupper($input['kurir']),
                'service' => $input['service'],
                'ongkir' => $input['ongkir'],
                'etd' => $input['etd'],
                'bukti_pembayaran' => $file ? $fileName : null,
            ]);
            return $this->resp($order);
        }
        $order = Order::find($id);
        if (!$order) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'address_id',
            'deskripsi',
            'tanggal_bayar',
            'no_rek',
            'atas_nama',
            'bukti_pembayaran'
        ]);
        $validator = Validator::make($input, [
            'address_id' => 'numeric',
            'deskripsi' => 'string',
            'no_rek' => 'required|string',
            'atas_nama' => 'required|string',
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'required|mimes:jpeg,png,jpg|max:2048',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $file = $request->file('bukti_pembayaran');
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
            'address_id' => $request->address_id ? $input['address_id'] : $order->address_id,
            'status_payment' => true,
            'deskripsi' => $request->deskripsi ? $input['deskripsi'] : $order->deskripsi,
            'no_rek' => $input['no_rek'],
            'atas_nama' => $input['atas_nama'],
            'tanggal_bayar' => $input['tanggal_bayar'],
            'bukti_pembayaran' => $file ? $fileName : null,
        ]);
    }

    public function ubahStatusOrder(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'status_order'
        ]);
        $validator = Validator::make($input, [
            'status_order' => 'required|numeric',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        if ($input['status_order'] == 3) {
            $od = OrderDetail::where('order_id', $order->id)->get();
            foreach ($od as $i => $v) {
                $produk = Produk::find($v->produk_id);
                $produk->update(['stok' => $produk->stok - $v->jumlah]);
            }
        }
        $order->update(['status_order' => $input['status_order']]);
        return $this->resp($order);
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
