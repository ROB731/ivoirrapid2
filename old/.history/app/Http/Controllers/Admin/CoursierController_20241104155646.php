<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoursierController extends Controller
{
    public function index()
    {
        return view('admin.coursiers.index');
    }

    public function create()
    {
        return view('admin.coursiers.create');
    }

    public function store(CourierRequest $request)
    {
        //
    }
}
