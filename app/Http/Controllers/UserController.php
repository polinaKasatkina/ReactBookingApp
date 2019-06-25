<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use JWTAuthException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function getUserData($id) {

        $user = User::where('id', $id)->first();


        return request()->json($user);

    }

}
