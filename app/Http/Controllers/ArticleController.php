<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\Article;

class ArticleController extends Controller
{


    const List = [];
    const count = 100;

    const baseContent = '<p>我是测试数据我是测试数据</p><p><img src="https://wpimg.wallstcn.com/4c69009c-0fd4-4153-b112-6cb53d1cf943"></p>';
    const image_uri = 'https://wpimg.wallstcn.com/e4558086-631c-425c-9430-56ffb46e70b3';


    public function index()
    {
        print_r('aaaaaaaaaaaaaaaaaaaaaaaaaaa');
        die;
        return Article::all();
    }

    public function show($id)
    {
        print_r('2222222');
        die;
        return Article::find($id);
    }

    public function store(Request $request)
    {
        return Article::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return 204;
    }


    public function login()
    {
//
//        print_r('dddddddddddddddddddddddddddddd');
//

        $dataTmp['roles'] = ['admin'];
        $dataTmp['token'] = 'admin';
        $dataTmp['introduction'] = '我是超级管理员';
        $dataTmp['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $dataTmp['name'] = 'Super Admin';
        $data['admin'] = $dataTmp;

        $dataTmp['roles'] = ['editor'];
        $dataTmp['token'] = 'editor';
        $dataTmp['introduction'] = '我是编辑';
        $dataTmp['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $dataTmp['name'] = 'Normal Editor';
        $data['editor'] = $dataTmp;

        return response()->json($data);
        return Article::find($id);
    }


    public function info()
    {

        $dataTmp['roles'] = ['admin'];
        $dataTmp['token'] = 'admin';
        $dataTmp['introduction'] = '我是超级管理员';
        $dataTmp['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $dataTmp['name'] = 'Super Admin';
        $data['admin'] = $dataTmp;


        return response()->json($data['admin']);

        return $data['admin'];

        $obj = (object)array('1' => 'foo');

        return $obj;

        $data['roles'] = 'admin';
        $data['token'] = 'admin';
        $data['introduction'] = '我是超级管理员';
        $data['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $data['name'] = 'Super Admin';

        $object = (object)$data;
        return $object;
        return Article::find($id);
    }


    public function list()
    {
        $aTmp = [];
        $aFinal = [];
        $iCount = 100;
        for ($i = 0; $i < $iCount; $i++) {
            $aTmp['id'] = $i;
            $aTmp['timestamp'] =strtotime('now');
            $aTmp['author'] = $i;
            $aTmp['reviewer'] = $i;
            $aTmp['title'] = $i;
            $aTmp['content_short'] = $i;
            $aTmp['content'] = $i;
            $aTmp['forecast'] = $i;
            $aTmp['importance'] = 2;
//            $aTmp['type'] = ['CN', 'US', 'JP', 'EU'];

            $aTmp['type'] = 'CN';
            $aTmp['status'] = 'published';
//            $aTmp['status'] = ['published', 'draft', 'deleted'];
            $aTmp['display_time'] = $i;
            $aTmp['comment_disabled'] = true;
            $aTmp['pageviews'] = $i;
            $aTmp['image_uri'] = $i;
            $aTmp['platforms'] = ['platforms01'];
            $aFinal[] = $aTmp;
        }
        $aFinal['items'] = $aFinal;
        $aFinal['total'] = 200;


        return response()->json($aFinal);

//        $object = (object)$aFinal;
//        return $object;

        return response()->json($aFinal);

    }


}
