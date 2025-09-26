<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function index()
    {
        $images = Image::all();
        return view('index', compact('images'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|min:6',
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
                Rule::dimensions()->maxWidth(1000)->maxHeight(1000)
            ]
        ]);

        if ($request->hasFile('image') && $request->title) {
            $image = $request->file('image');

            $hashname = $image->hashName();

            $image->storePublicly('uploads', 'public', $hashname);

            $title = $request->only('title');

            $path = "uploads/$hashname";

            try {
                Image::create([
                    'title' => $title['title'],
                    'hashname' => $hashname
                ]);
            } catch (Exception $error) {
                Storage::disk('public')->delete($path);
                return redirect()->back()->withErrors([
                    'error' => 'Erro ao salvar a imagem. Tente novamente.'
                ]);
            }
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
