<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = Image::all();
        return view('index', compact('images'));
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('image') && $request->title) {
            $image = $request->file('image');

            $hashname = $image->hashName();

            $image->storePublicly('uploads', 'public', $hashname);

            $title = $request->only('title');

            Image::create([
                'title' => $title['title'],
                'hashname' => $hashname
            ]);
        }

        return redirect(route('index'));
    }

    public function delete($id)
    {
        $image = Image::findOrFail($id);
        $path = "uploads/$image->hashname";

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            $image->delete();
        }

        return redirect(route('index'));
    }
}
