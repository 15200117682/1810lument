<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function curl3()
    {
        $res = file_get_contents("php://input");
        $data = json_decode($res, true);
        if ($data['name'] == "") {
            $res = [
                'error' => 50001,
                'msg' => '名称必填',
            ];
            return json_encode($res, JSON_UNESCAPED_UNICODE);
        } else if ($data['pwd'] == "") {
            $res = [
                'error' => 50002,
                'msg' => '密码必填',
            ];
            return json_encode($res, JSON_UNESCAPED_UNICODE);
        }
        $res = [
            'error' => 0,
            'msg' => '登陆成功'
        ];
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //对称加密
    public function encryption()
    {
        $res = file_get_contents("php://input");
        $method = "AES-128-CBC";
        $key = "yuanfen";
        $iv = "rfdefgvcsxewqjgh";
        $data = openssl_decrypt($res, $method, $key, OPENSSL_RAW_DATA, $iv);

        var_dump($data);
    }

    //非对称加密
    public function noEncryption()
    {
        $enc_data = file_get_contents("php://input");
        //var_dump($enc_data);exit;
        $in = openssl_get_publickey('file://' . storage_path('soft/rsa_public_key.pem'));
        openssl_public_decrypt($enc_data, $noenc_data, $in);
        echo "解密数据：" . $noenc_data;
    }

    //验证签名
    public function signature()
    {
        $data = $_POST;//接收所有数据
        $sign = base64_decode($_POST['sign']);//解密签名
        unset($data['sign']);//去除签名
        $str0 = "";
        foreach ($data as $k => $v) {
            $str0 .= $k . "=" . $v . "&";
        }//拼接数据
        $str = rtrim($str0, "&");//去除右侧的&符号
        $key = openssl_get_publickey('file://' . storage_path("soft/rsa_public_key.pem"));//获取公钥
        $or = openssl_verify($str, $sign, $key);//验证签名
        if ($or == 1) {
            return "ok";
        } else {
            return "签名不正确";
        }
    }
}
