<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\Controller;
use Illuminate\Support\Facades\App;

class TranslationController extends Controller
{
    public function getAll()
    {
        $locale = App::getLocale();
        $path = base_path('lang' . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . $locale . '.json');
        $fileContents = file_get_contents($path);
        return response()->json(json_decode($fileContents));
    }
}
