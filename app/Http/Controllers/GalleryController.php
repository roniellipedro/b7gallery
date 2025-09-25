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
        // $title = $request->only('title');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
        }

        $name = $image->hashName();

        $return = $image->storePublicly('uploads', 'public', $name);

        dd($return);
    }

    public function delete() {}
}
