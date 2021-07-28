<?php

namespace App\Http\Controllers;

use App\Models\test;
use Illuminate\Http\Request;

class tests extends Controller
{
    //read
    public function lihatTest()
    {
        return $this->resp(test::all());
    }

    public function inputTest(Request $request)
    {
        # code...
        $input = $request->only('nama', 'umur');

        $addTest = test::create($input);
        return $this->resp($addTest);
    }

    public function deleteTest($id)
    {
        # code...
        $data = test::find($id);
        $data->delete();

        return $this->resp($data);
    }

    public function updateTest(Request $request, $id)
    {
        # code...
        $data = test::find($id);
        $input = $request->only('nama', 'umur');

        $data -> update($input);
        return $this->resp($data);
    }
}
