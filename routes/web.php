<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post("/user/login","Login\LoginController@login");//测试登陆
$router->post("/user/register","Login\LoginController@register");//测试注册
$router->post("/user/updatepwd","Login\LoginController@updatepwd");//测试修改密码

$router->post("/test/curl3","Test\TestController@curl3");//简单的完整的接口
$router->post("/test/encryption","Test\TestController@encryption");//对称加密
$router->post("/test/no_enc","Test\TestController@noEncryption");//非对称加密
$router->post("/test/signature","Test\TestController@signature");//验证签名
