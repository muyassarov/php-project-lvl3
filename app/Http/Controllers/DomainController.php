<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function store()
    {

    }

    public function index()
    {
        return view('domains.index');
    }
}
