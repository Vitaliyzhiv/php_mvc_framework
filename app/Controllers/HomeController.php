<?php

namespace App\Controllers;

class HomeController extends BaseController
{

    public function index()
    {
        // использование  функции  view из helpers 
        return view('home', ['title' => 'Home page']);
        //app()->view->render('test', ['name' => 'Jonh','age' => 30]);  
        
    }

    public function contact()
    {
        return 'Contact page';
    }
 
}