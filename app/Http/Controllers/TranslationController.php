<?php

namespace Foundry\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

/**
 * Class TranslationController
 *
 * @package Foundry\System\Http\Controllers\Api
 */
class TranslationController extends Controller
{
    public function set($lang = 'en', Request $request){
        //Todo save lang to users' profile
        session()->put('lang', $request->get('lang', $lang));
        return Redirect::back();
    }
}
