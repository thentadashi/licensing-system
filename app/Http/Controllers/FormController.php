<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class FormController extends Controller
{
    public function index()
    {
        $files = collect(File::files(public_path('downloadable_forms')))
            ->sortBy(fn($f) => $f->getFilename());

        return view('forms.index', compact('files'));
    }
}
