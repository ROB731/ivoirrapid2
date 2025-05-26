<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CoursierFormRequest;
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

    public function store(CoursierFormRequest $request)
    {
        $data = $request->validated();

        
    }
}
