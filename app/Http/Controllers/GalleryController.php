<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function upload(Request $request)
    {
        dd($request);
    }

    public function delete() {}
}
