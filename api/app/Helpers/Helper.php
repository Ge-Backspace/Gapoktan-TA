<?php
namespace App\Helpers;

use App\Models\Files\File;
use App\Models\Files\MultiFile;
use GuzzleHttp\Client;

class Helper {
    /**
     * @param $errorMessage
     * @return array
     */
    public static function generateErrorMsg($errorMessage)
    {
        $msg = [];
        foreach ($errorMessage as $key => $m) {
            $msg[$key] = $m[0];
        }
        return $msg;
    }

    /**
     * messageValidation
     *
     * @return void
     */
    public static function messageValidation()
    {
        return [
            'required' => 'Tidak boleh kosong',
            'string' => 'Harus berupa string',
            'numeric' => 'Harus berupa angka',
            'url' => 'Harus berupa URL',
            'array' => 'Harus berupa array',
            'boolean' => 'Harus berupa true / false / 1 / 0',
            'unique' => 'Sudah pernah digunakan',
            'image' => 'Harus berupa gambar',
            'mimetypes' => 'Format tidak diperbolehkan',
            'max' => 'Tidak boleh lebih dari :max',
            'min' => 'Tidak boleh kurang dari :min',
            'mimes' => 'Harus berformat :values',
            'regex' => 'Format tidak valid',
            'in' => 'Harus salah satu dari :values',
            'date' => 'Harus berupa tgl',
            'date_format' => 'Harus berformat :format',
            'after' => 'Tgl tidak boleh lebih kecil dari start',
            'exists' => 'Tidak ditemukan'
        ];
    }

    /**
     * storeFile
     *
     * @param  mixed $mode
     * @param  mixed $type
     * @param  mixed $field
     * @param  mixed $request
     * @param  mixed $parent
     * @param  mixed $multiple
     * @param  mixed $toMultiFile
     * @return void
     */
    public static function storeFile($mode, $type, $field, $request, $parent = null, $multiple = false, $toMultiFile = false){
        if ($request->file($field)) {
            $type = $type;
            $basePath = base_path('storage/app/public/' . $type);
            if($multiple){
                if($toMultiFile){
                    $file = $request->file($field);
                } else {
                    $fieldName = $request->{$field[0]};
                    $file = $fieldName[$field[1]];
                }
            } else {
                $file = $request->file($field);
            }
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
            if($mode == 'store'){
                $dataFile = File::create($newFile);
                if($dataFile){
                    $file->move($basePath, $fileName);
                    if($parent && $multiple){
                        $multi_file = [
                            'id_file' => $dataFile->id,
                            'id_parent' => $parent->id,
                            'type' => $type
                        ];
                        MultiFile::create($multi_file);
                        return true;
                    } else {
                        return $dataFile->id;
                    }
                } else {
                    return false;
                }
            } else {
                if($multiple){
                    $dataFile = File::create($newFile);
                    if($dataFile){
                        $multi_file = [
                            'id_file' => $dataFile->id,
                            'id_parent' => $parent->id,
                            'type' => $type
                        ];
                        MultiFile::create($multi_file);
                        $file->move($basePath, $fileName);
                        return true;
                    }
                } else {
                    // $dataFile = $parent->file()->first();
                    // if(!$dataFile){
                        $dataFile = File::create($newFile);
                        $parent->file_id = $dataFile->id;
                        $parent->save();
                        $file->move($basePath, $fileName);
                        return true;
                    // }
                    // if($dataFile){
                    //     $oldFile = $basePath . '/' . $dataFile->file_name;
                    //     if(file_exists($oldFile)){
                    //         unlink($oldFile);
                    //     }
                    //     $dataFile->update($newFile);
                    //     $file->move($basePath, $fileName);
                    //     return true;
                    // }
                }
            }
        }

        return true;
    }

    /**
     * clearMultiFile
     *
     * @param  mixed $model
     * @param  mixed $type
     * @return void
     */
    public static function clearMultiFile($model, $type)
    {
        $multi_file = $model->multiFile;
        $basePath = base_path('storage/app/public/' . $type);
        foreach($multi_file as $mf){
            $oldFile = $basePath . '/' . $mf->file_name;
            if(file_exists($oldFile)){
                unlink($oldFile);
            }
            $mf->delete();
        }
    }


   /**
   * api
   *
   * @param  mixed $method
   * @param  mixed $url
   * @param  mixed $form_data
   * @return void
   */
  public static function api($method, $url, $form_data = [])
  {
      $client = new Client(['verify' => false]);
      $response = $client->request($method, $url, $form_data);

      return json_decode($response->getBody()->getContents());
  }
}
