<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */




//第一个问题响应头
//Access-Control-Allow-Credentials:true
//Access-Control-Allow-Origin:*
//Content-Type:text/x-json;charset=UTF-8
//Date:Fri, 27 Apr 2018 02:12:18 GMT
//Transfer-Encoding:chunked
//Vary:Origin
//消息头
//Request URL:http://10.0.101.24:8080/SCMApp/base/custom/logingExtBusiness/login
//Request Method:POST
//Status Code:200
//Remote Address:10.0.101.24:8080
//Referrer Policy:no-referrer-when-downgrade



//header('Access-Control-Allow-Origin:*');
//
//header('Access-Control-Allow-Headers:cache-control,x-adminid,x-token');



header('Access-Control-Allow-Credentials:true');
//header('Access-Control-Allow-Origin:http://192.168.36.226:9527');
header('Content-Type:text/x-json;charset=UTF-8');
header('Date:Fri, 27 Apr 2018 02:12:18 GMT');
header('Transfer-Encoding:chunked');
header('Vary:Origin');





//Access-Control-Allow-Credentials: true
//Access-Control-Allow-Headers: Content-Type,Access-Token
//Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS
//Access-Control-Allow-Origin: *
//Access-Control-Expose-Headers: *
//Allow: GET, HEAD
//Cache-Control: no-cache, private
//Connection: keep-alive
//Content-Type: application/json
//Date: Tue, 01 Jan 2019 09:57:32 GMT
//Server: nginx
//Transfer-Encoding: chunked
//X-Powered-By: PHP/7.2.6




//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
////header("Access-Control-Allow-Methods", "POST, GET, OPTIONS,DELETE,PUT");
////header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, X-CSRF-TOKEN');
//header('Access-Control-Allow-Credentials: true');
//header('Content-Type: text/x-json;charset=UTF-8');
//header('Transfer-Encoding: chunked');
////header('Allow: GET, HEAD, POST');


//header("Access-Control-Allow-Origin", "*");
//header("Access-Control-Allow-Methods", "POST, GET, OPTIONS,DELETE,PUT");
//header("Access-Control-Allow-Headers", "Test");




//header("Access-Control-Request-Method", "true");
//header("Access-Control-Request-Headers", "true");
//header("Access-Control-Allow-Origin", "true");
//header("Access-Control-Allow-Methods", "true");
//header("Access-Control-Allow-Headers", "true");


//header("Access-Control-Allow-Origin", "*");
//header("Access-Control-Allow-Headers", "X-Requested-With,Content-Type");
//header("Access-Control-Allow-Methods","PUT,POST,GET,DELETE,OPTIONS");



//报告运行时错误

error_reporting(E_ERROR | E_WARNING | E_PARSE);

//报告所有错误

error_reporting(E_ALL);

ini_set("display_errors","On");






define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
