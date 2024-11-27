<?php

namespace App\Controllers;

class ContactController extends BaseController
{

    public function index()
    {
        dump(send_mail(
            ['test@mail.com'],
            'Test send mail',
            'mail/test',
            ['name' => 'John Doe', 'age' => 35],
            [WWW . '/img/default_image.jpg']
        ));
        // использование  функции  view из helpers 
        return view('contact/index', ['title' => 'Contact page']);
        //app()->view->render('test', ['name' => 'Jonh','age' => 30]);  

    }
}
