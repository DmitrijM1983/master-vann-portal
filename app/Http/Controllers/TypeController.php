<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TypeController extends Controller
{
    public function enamelInfo(): View
    {
        return view('types.enamel');
    }

    public function acrylicInfo(): View
    {
        return view('types.acrylic');
    }

    public function linerInfo(): View
    {
        return view('types.liner');
    }
}
