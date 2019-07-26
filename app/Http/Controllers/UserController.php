<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Auth;

use App\User;
use App\File;

class UserController extends Controller
{

    public function profile( Request $request): View
    {

        $search = '';
        $user = Auth::user();

//        $request->session()->forget('notified');
        if ( $request->session()->has('notified') || $request->session()->get('notified') == 1) {
            $user->notified = 'true';
        } else {
            $user->notified = 'false';
            $request->session()->put('notified', 1);

        }
        $user = UserController::userInterface( $user);

        return view('user.profile', compact('user','search'));
    }

    public function editing(): View
    {
        $search = '';
        $user = Auth::user();

        $user = UserController::userInterface( $user);

        return view('user.edit', compact('user','search'));
    }

    public function edit(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'title' => 'min:3',
        ]);
        $user = User::find(Auth::user()->id);
        if ( $request->name != null) {  $user->name = $request->name;}
        if ( $request->blood_type != null) {  $user->blood_type = $request->blood_type;}
        if ( $request->rhesus != null) {  $user->rhesus = $request->rhesus;}
        if ( $request->description != null) {  $user->description = $request->description;}

        $uploadedFile = $request->file('file');
        if ( $uploadedFile != null) {
            $path = $uploadedFile->store('public/files');
            $file = File::create([
                'title' => $request->title ?? $uploadedFile->getClientOriginalName(),
                'filename' => $path
            ]);
            $user->file_id = $file->id;
        }
        $user->save();

        return redirect( route('user.profile'))
            ->withSuccess(sprintf('Your profile has been edited.', $user->name));

    }

    public function fileupload(Request $request): RedirectResponse
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

        $user = User::find(Auth::user()->id);
        $user->file_id = $file->id;
        $user->save();

        return redirect()
            ->back()
            ->withSuccess(sprintf('File %s has been uploaded.', $file->title));

    }

    public function last4monthtimestamp( $timestamp) {
        $today_start_ts = strtotime(date('Y-m-d', time()). ' 00:00:00');
        $today = $today_start_ts; $numberofpeople = 10;

        // echo date('Y-m-d H:i:s', $startdate) . "<br>";
        $todate = date('Y-m-d H:i:s', $today);

        $yesterday = $today - 60*60*24; // 1hari yang lalu
        $yesterdate = date('Y-m-d H:i:s', $yesterday);

        $last4months = $today - 60*60*24*30*4; // 4bulan yang lalu
        $last4monthsdate = date('Y-m-d H:i:s', $last4months);

        return $last4months;
    }

    public static function userInterface( $user) {
        if ( $user->file_id) {
            $file = File::find($user->file_id);
        }
        

        if (!$user->file_id || !$file) {
            $user->filename = 'public/files/default.jpg';
        } else {
            $user->filename = $file->filename;
        }
        if ( $user->blood_type == null) {
            $user->blood_type_rhesus = 'Dont know';
        } else {
            $user->blood_type_rhesus = $user->blood_type . $user->rhesus;
        }
        if ( $user->last_donor == null) {
            $user->last_donor = '00-00-0000';
        } else {
            $user->last_donor = date("d-m-Y",  strtotime($user->last_donor));
        }
        if ( $user->last_donor == null ||strtotime($user->last_donor) <= DonorController::last4monthtimestamp(strtotime($user->last_donor))) {
            $user->donor_eligibility = 'Yes';
        } else {
            $user->donor_eligibility = 'No';
        }
        return $user;
    }
}