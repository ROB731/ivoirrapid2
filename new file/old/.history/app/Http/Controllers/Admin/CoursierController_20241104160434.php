<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coursier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CoursierFormRequest;

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

        $coursier = new Coursier;

        $coursier->name = $data['name'];
        $coursier->save();
    }
}
