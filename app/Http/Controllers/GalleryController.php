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
        $this->validateRequest($request);

        $image = $request->file('image');
        $hashname = $image->hashName();
        $title = $request->only('title');

        $path = $this->storeImageInDisk($image, $hashname);

        try {
            $this->storageImageInDatabase($title['title'], $hashname);
        } catch (Exception $error) {
            Storage::disk('public')->delete($path);
            $imageForDelete = Image::firstWhere('hashname', $hashname)?->delete();
            return redirect()->back()->withErrors([
                'error' => 'Erro ao salvar a imagem no banco. Tente novamente.'
            ]);
        }

        return redirect(route('index'));
    }

    private function validateRequest(Request $request)
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
    }

    private function storeImageInDisk($image, $hashname)
    {
        $image->storePublicly('uploads', 'public', $hashname);
        return "uploads/$hashname";
    }

    private function storageImageInDatabase($title, $hashname)
    {
        Image::create([
            'title' => $title,
            'hashname' => $hashname
        ]);
    }

    public function delete($id)
    {
        $image = Image::findOrFail($id);
        $path = "uploads/$image->hashname";

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $image->delete();

        return redirect(route('index'));
    }
}
