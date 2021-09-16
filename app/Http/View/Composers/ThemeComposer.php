<?php

namespace App\Http\View\Composers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;


class ThemeComposer
{

    public function __construct()
    {
       //
    }

    public function compose (View $view){

         $theme = Cookie::get('theme');

         if(empty($theme)){
             $theme = 'dark';
         }
         //Log::info('We got the theme '. $theme );

         $view->with('theme',$theme);

    }
}
