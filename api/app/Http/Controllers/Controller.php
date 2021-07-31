<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\FotoProfil;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use Spatie\Geocoder\Geocoder;

class Controller extends BaseController
{
    /**
     * resp
     *
     * @param  mixed $data
     * @param  mixed $success
     * @param  mixed $message
     * @param  mixed $merge
     * @param  mixed $status_code
     * @return void
     */
    public function resp($data = null, $message = Variable::SUCCESS, $success = true, $status_code = 200)
    {
        $result = [
            'success' => $success,
            'message' => $message,
            'code' => $status_code,
            'data' => $data
        ];
        return response()->json($result, $status_code);
    }

    /**
     * respMerge
     *
     * @param  mixed $data
     * @param  mixed $success
     * @param  mixed $message
     * @param  mixed $meta
     * @return void
     */
    public function respMerge($data = null, $message = Variable::SUCCESS, $success = true, $status_code = 200)
    {
        $result = collect([
            'success' => $success,
            'message' => $message,
            'code' => $status_code,
        ]);
        $result = $result->merge($data);
        return response()->json($result);
    }

    /**
     * getData
     *
     * @param  mixed $model
     * @param  mixed $request
     * @param  mixed $filterable
     * @return void
     */
    public function getData($model, $request, $filterable = [])
    {
        $model = $model->where(function($query)use($filterable, $request){
            foreach($filterable as $column){
                $query->orWhereRaw("CONCAT($column, '')  LIKE ?", ['%' . $request->search . '%']);
            }
        });

        return $this->resp($model->get());
    }

    /**
     * getPaginate
     *
     * @param  mixed $model
     * @param  mixed $request
     * @return void
     */
    public function getPaginate($model, $request, $filterable = [])
    {
        $model = $model->where(function($query)use($filterable, $request){
            foreach($filterable as $column){
                $query->orWhereRaw("CONCAT($column, '')  LIKE ?", ['%' . $request->search . '%']);
            }
        });

        $limit = $request->has('limit') ? (int) $request->limit : 10;
        return $this->respMerge($model->paginate($limit)->appends($request->except('page')));
    }

    /**
     * storeData
     *
     * @param  mixed $model
     * @param  mixed $rules
     * @param  mixed $input
     * @param  mixed $single_file
     * @param  mixed $multiple_files
     * @return void
     */
    public function storeData($model, $rules, $input, $single_file = [], $multiple_files = [])
    {
        $validator = Validator::make($input, $rules, Helper::messageValidation());

        if($validator->fails()){
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }

        if(!empty($single_file)){
            $storeFile = Helper::storeFile('store', $single_file['type'], $single_file['field'], request());
            if($storeFile){
                $input['file_id'] = $storeFile;
            } else {
                return $this->resp(null, Variable::FAILED_UPLOAD, false, 400);
            }
        }

        $model = $model::create($input);

        if(!empty($multiple_files)){
            foreach($multiple_files as $key => $d_file){
                Helper::storeFile('store', $d_file['type'], [$d_file['field'], $key], request(), $model, true);
            }
        }

        if($model){
            return $this->resp($model);
        }
        return $this->resp(null, Variable::FAILED, false);
    }

    /**
     * updateData
     *
     * @param  mixed $model
     * @param  mixed $rules
     * @param  mixed $input
     * @param  mixed $single_file
     * @param  mixed $multiple_files
     * @return void
     */
    public function updateData($model, $rules, $input, $single_file = [], $multiple_files = [])
    {
        $validator = Validator::make($input, $rules, Helper::messageValidation());

        if($validator->fails()){
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }

        if(!$model){
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }

        if(!empty($single_file)){
            $storeFile = Helper::storeFile('update', $single_file['type'], $single_file['field'], request(), $model);
            if(!$storeFile){
                return $this->resp(null, Variable::FAILED_UPLOAD, false, 400);
            }
        }

        if(!empty($multiple_files)){
            Helper::clearMultiFile($model, $multiple_files[0]['type']);
            foreach($multiple_files as $d_file){
                Helper::storeFile('update', $d_file['type'], $d_file['field'], request(), $model, true);
            }
        }

        if($model->update($input)){
            return $this->resp($model);
        }
        return $this->resp(null, Variable::FAILED, false);
    }

    /**
     * showData
     *
     * @param  mixed $model
     * @return void
     */
    public function showData($model)
    {
        if($model){
            return $this->resp($model);
        } else {
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }
    }

    /**
     * deleteData
     *
     * @param  mixed $model
     * @return void
     */
    public function deleteData($model)
    {
        if($model){
            $model->delete();
            return $this->resp();
        } else {
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }
    }

    public function fotoProfil($file)
    {
        $type = Variable::USER;
        $basePath = base_path('storage/app/public/' . $type);
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            $extension = $file->clientExtension();
        }
        $fileName = $type . '-' . time() . '.' . $extension;
        $newFile = [
            'file_name' => $fileName,
            'path' => $fileName,
            'size' => $file->getSize(),
            'extension' => $extension,
        ];
        $dataFile = FotoProfil::create($newFile);
        $file->move($basePath, $fileName);
        return $dataFile->id;
    }
}
