<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index() {
        $properties = Property::all();
        return view('backend.properties.index', compact('properties'));
    }
    public function show($id) {
        $property = Property::findOrFail($id);
        return view('backend.properties.show', compact('property'));
    }
    public function destroy($id) { /* delete logic */ return back(); }
    public function toggleFeatured($id) { /* feature/unfeature logic */ return back(); }
}
