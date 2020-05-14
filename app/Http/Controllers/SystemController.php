<?php


namespace Foundry\System\Http\Controllers;


class SystemController extends Controller
{

    public function mailcatcher()
    {
        $ip = gethostbyname( $_SERVER['HTTP_HOST'] );
        return redirect( 'http://' . $ip . ":1080" );
    }

    public function backend()
    {
        return file_get_contents(base_path('public/backend/index.html'));
    }

}
