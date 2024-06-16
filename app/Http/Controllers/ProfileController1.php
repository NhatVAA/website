<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    //
    // Contructor
    public function __construct(){
        //
    }

    // GET | Hàm lấy trang cá nhân của mình, hoặc của người khác (với tham số $idUser)
    public function profile($idUser = ""){
        if($idUser != ""){
            return auth()->user();
        }
    }
}
