<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::all();
        return view('admin.class.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.class.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_name' => 'required|string|max:255',
            'class_code' => 'required|string|max:255|unique:classes,class_code',
        ]);

        Classes::create($validatedData);

        return redirect()->route('admin.class.index')->with('success', __('sweetalert.flash.class.created'));
    }

    public function edit(string $class)
    {
        $class = Classes::findOrFail($class);
        return view('admin.class.edit', compact('class'));
    }

    public function update(Request $request, string $class)
    {
        $validatedData = $request->validate([
            'class_name' => 'required|string|max:255',
            'class_code' => 'required|string|max:255|unique:classes,class_code,' . $class,
        ]);

        $class = Classes::findOrFail($class);
        $class->update($validatedData);

        return redirect()->route('admin.class.index')->with('success', __('sweetalert.flash.class.updated'));
    }

    public function destroy(string $class)
    {
        $class = Classes::findOrFail($class);
        $class->delete();

        return redirect()->route('admin.class.index')->with('success', __('sweetalert.flash.class.deleted'));
    }
}
