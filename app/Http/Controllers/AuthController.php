<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\Article;

class AuthController extends Controller
{
    public function index()
    {
        print_r('aaaaaaaaaaaaaaaaaaaaaaaaaaa');
        die;
        return Article::all();
    }

    public function show($iId)
    {
        print_r('2222222');
        die;
        return Article::find($iId);
    }

    public function store(Request $request)
    {
        return Article::create($request->all());
    }

    public function update(Request $request, $iId)
    {
        $article = Article::findOrFail($iId);
//        $article''$request->all());

        return $article;
    }

    public function delete(Request $request, $iId)
    {
        $article = Article::findOrFail($iId);
        $article->delete();

        return 204;
    }
}
