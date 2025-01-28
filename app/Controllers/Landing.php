<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Landing extends BaseController
{
    public function index()
    {
        return view('pages/landing');
    }
}
