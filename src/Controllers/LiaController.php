<?php

namespace Lia\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LiaController extends Controller{

    public function index(Request $request)
    {
        return view('lia::index');
    }

}