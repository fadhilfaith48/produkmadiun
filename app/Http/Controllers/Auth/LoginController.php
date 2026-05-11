<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    /**
     * Di Laravel 12, kita tidak perlu lagi memanggil $this->middleware() 
     * di dalam constructor karena fungsi tersebut sudah dihapus dari base controller.
     * Secara default, Auth::routes() sudah menangani keamanan halaman login.
     */
    public function __construct()
    {
        // Biarkan kosong atau hapus saja fungsi __construct ini
    }
}