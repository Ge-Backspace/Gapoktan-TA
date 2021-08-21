<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Artikel;
use App\Models\Gapoktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    public function lihatArtikel(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $gapoktan = Gapoktan::where('user_id', $request->user_id)->first();
            if(!$gapoktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $table = Artikel::join('gapoktans', 'gapoktans.id', '=', 'artikels.gapoktan_id')
        ->where('gapoktans.id', $state ? $gapoktan->id : $request->gapoktan_id)
        ->select(DB::raw('artikels.*, gapoktans.nama as published'))
        ->orderBy('artikels.id', 'DESC');
        return $this->getPaginate($table, $request, ['artikels.judul', 'artikels.isi']);
    }

    public function lihatSemuaArtikel(Request $request)
    {
        return $this->getPaginate(Artikel::query()->orderBy('id', 'DESC'), $request, ['judul', 'isi']);
    }

    public function tambahArtikel(Request $request)
    {
        $input = $request->only(['gapoktan_id', 'judul', 'isi', 'foto']);
        $validator = Validator::make($input, [
            'judul' => 'required',
            'isi' => 'required',
            'gapoktan_id' => 'numeric',
            'foto' => 'mimes:jpeg,png,jpg|max:5048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $gapoktan = Gapoktan::where('user_id', $request->user_id)->first();
            if(!$gapoktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $file = $request->foto;
        if ($file) {
            $basePath = base_path('storage/app/public/' . Variable::ARTL);
            $extension = $file->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $file->clientExtension();
            }
            $fileName = Variable::ARTL . '-' . time() . '.' . $extension;
            $file->move($basePath, $fileName);
        }
        $add = Artikel::create([
            'judul' => $input['judul'],
            'isi' => $input['isi'],
            'gapoktan_id' => $state ? $gapoktan->id : $input['gapoktan_id'],
            'foto' => $file ? $fileName : null,
        ]);
        return $this->resp($add);
    }

    public function ubahArtikel(Request $request, $id)
    {
        $input = $request->only(['judul', 'isi']);
        $validator = Validator::make($input, [
            'judul' => 'required',
            'isi' => 'required',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $data = Artikel::find($id);
        $file = $request->foto;
        if ($file) {
            $basePath = base_path('storage/app/public/' . Variable::ARTL);
            $extension = $file->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $file->clientExtension();
            }
            $fileName = Variable::ARTL . '-' . time() . '.' . $extension;
            $file->move($basePath, $fileName);
        }
        if (!$data) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $inputUpdate = [
            'judul' => $input['judul'],
            'isi' => $input['isi'],
            'foto' => $file ? $fileName : null
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusArtikel($id)
    {
        try {
            Artikel::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
