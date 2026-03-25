<?php

namespace App\Http\Controllers;

use App\Models\Sesi;
use App\Models\SesiRenstra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    function login(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'username.required' => 'Username Harus Di Isi',
                'password.required' => 'Password Harus Di Isi',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Username atau Password Tidak Boleh Kosong'
                ],
                200
            );
        }

        $data = [
            'username'  => $request->username,
            'password'  => $request->password,
        ];

        Auth::guard('admin')->attempt($data);
        
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if ($user->admin_role == 1) {
                $otorisasi = is_array($user->admin_otorisasi) 
                     ? $user->admin_otorisasi 
                     : json_decode($user->admin_otorisasi, true);

                Session::put('session_instansi', $otorisasi[0]);
            } else {
                Session::put('session_instansi', NULL);
            }

            Session::put('session_tahun', $request->tahun);
            $sesi = Sesi::where('sesi_tahun', $request->tahun)->where('sesi_status', 1)->first();
            Session::put('session_kode', $sesi);

            $passwordYangDicek = $request->password;

            $validator = Validator::make(['password' => $passwordYangDicek], [
                'password' => [
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ],
            ]);

            if ($validator->fails()) {
                Session::put('session_password', false);
            }

            Session::put('session_password', true);

            $sesi_renstra = SesiRenstra::where('sesi_renstra_status', 1)->first();

            Session::put('session_renstra', $sesi_renstra);
            
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false, 'message' => 'Username atau Password Salah']);
        }
    }

    public function logout(Request $request)
    {   
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        Session::forget('session_tahun');
        Session::forget('session_kode');
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }




}
