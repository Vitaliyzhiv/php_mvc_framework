<?php

namespace App\Controllers;

use App\Models\User;
// use PHPFramework\Pagination;
// use Illuminate\Database\Capsule\Manager as Capsule;

class PostController extends BaseController
{



    // загрузка вида страницы posts
    public function index()
    {

        // dump(app()->get('lang'));
        $posts = db()->query(
            "select p.*, pd.* from posts p join posts_description pd on p.id = pd.post_id  where pd.lang_id = ?" , [app()->get('lang')['id']] 
        )->get();

        return view('post/index', [
            'title' => 'Список статей',
            'posts' => $posts,
        ]);
    }
}
