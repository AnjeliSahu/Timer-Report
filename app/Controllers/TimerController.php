<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TimerController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Timer Report',
            'page_title' => 'Timer Report Dashboard'
        ];

        return view('layouts/main', $data);
    }
}