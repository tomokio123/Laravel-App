<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){
        //resource/viewsの中のuser/index.blade.phpのこと
        return view('user.index');
    }
}
