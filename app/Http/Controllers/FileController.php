<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\File;
use Illuminate\Http\RedirectResponse;

class FileController extends Controller
{
    // Referensi https://www.laravel.web.id/2017/11/20/upload-file-menggunakan-filesystem-laravel/
    public function index(): View
    {
        $files = File::orderBy('created_at', 'DESC')
            ->paginate(30);
        return view('file.index', compact('files'));
    }

    public function form(): View
    {
        return view('file.form');
    }
    public function upload(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'title' => 'nullable|max:100',
            'file' => 'required|file|max:2000', // max 2MB
        ]);

        // tampung berkas yang sudah diunggah ke variabel baru
        // 'file' merupakan nama input yang ada pada form
        $uploadedFile = $request->file('file');

        // simpan berkas yang diunggah ke sub-direktori 'public/files'
        // direktori 'files' otomatis akan dibuat jika belum ada
        $path = $uploadedFile->store('public/files');

        $file = File::create([
            'title' => $request->title ?? $uploadedFile->getClientOriginalName(),
            'filename' => $path
        ]);

        // Untuk mengambil gambar
        // $path = 'public/files/UCEQ98OX61VUZHwT7iSRUAUwRbTyW49q2IZB0fBm.jpeg'
        // $publicPath = \Storage::url($path);

        return redirect()
            ->back()
            ->withSuccess(sprintf('File %s has been uploaded.', $file->title));

    }
}
