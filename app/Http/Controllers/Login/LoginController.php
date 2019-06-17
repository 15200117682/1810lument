<?php

namespace App\Http\Controllers\Login;

use App\Model\UserModel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;


class LoginController extends Controller
{
    //登陆
    public function login(Request $request){
        $name=$request->input('name');//账号
        $pwd=$request->input('pwd');//密码
        if(empty($name)){
            $text="账号不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        if(empty($pwd)){
            $text="密码不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        $obj=UserModel::where(['user_name'=>$name])->first();//根据账号查数据
        $arr=json_decode($obj,true);//转数组格式数据
        if($arr){//判断账号存在
            //判断密码是否正确
            if($pwd===$arr['user_pwd']){//判断密码
                $text="登陆成功";
                $msg=$this->access($text);
                return $msg;
            }else{
                //错误密码
                $text="账号密码不正确";
                $msg=$this->error($text);
                return $msg;
            }
        }else{
            //错误账号
            $text="账号密码不存在";
            $msg=$this->error($text);
            return $msg;
        }
    }

    /**
     * 注册
     */
    public function register(Request $request){
        $name=$request->input('name');//账号
        $password=$request->input('password');//密码
        $pwd=$request->input('pwd');//确认密码
        $email=$request->input('email');//确认密码
        if(empty($name)){
            $text="账号不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        if(empty($password)){
            $text="密码不能为空";
            return $this->error($text);
        }
        if(empty($pwd)){
            $text="确认密码不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        if(empty($email)){
            $text="邮箱不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        $obj=UserModel::where(['user_name'=>$name])->first();//查数据
        $arr=json_decode($obj,true);//转换成数组
        if($arr){//账号存在则无法注册
            $text="账号已存在，无法注册";
            $msg=$this->error($text);
            return $msg;
        }else{//判断两次密码是否一致
            if($pwd!=$password){
                $text="两次密码输入不正确";
                $msg=$this->error($text);
                return $msg;
            }else{
                $insert=["user_name"=>$name,"user_pwd"=>$pwd,"email"=>$email];//组装数据
                $res=UserModel::insertGetId($insert);
                if($res){
                    $text="注册成功";
                    $msg=$this->access($text);
                    return $msg;
                }else{
                    $text="注册失败";
                    $msg=$this->error($text);
                    return $msg;
                }

            }
        }
    }

    //修改密码
    public function updatepwd(Request $request){
        $name=$request->input('name');//账号
        $oldpwd=$request->input('pwd');//旧密码
        $newpwd=$request->input('newpwd');//新密码
        if(empty($name)){
            $text="账号不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        if(empty($oldpwd)){
            $text="密码不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        if(empty($newpwd)){
            $text="密码不能为空";
            $msg=$this->error($text);
            return $msg;
        }
        $obj=UserModel::where(['user_name'=>$name])->first();//查数据
        $arr=json_decode($obj,true);//转数组格式数据
        if($arr){
            if($arr['user_pwd']===$oldpwd){
                $arr=[
                    'user_pwd'=>$newpwd
                ];
                $res=UserModel::where(['user_name'=>$name])->update($arr);//查数据
                if($res){
                    $text="密码修改成功";
                    $msg=$this->access($text);
                    return $msg;
                }else{
                    $text="密码修改失败";
                    $msg=$this->error($text);
                    return $msg;
                }
            }else{
                $text="输入账号密码错误";
                $msg=$this->error($text);
                return $msg;
            }
        }else{
            $text="输入账号密码错误";
            $msg=$this->error($text);
            return $msg;
        }
    }

    //返回正确结果
    public function access($text){
        $arr=[
            "msg"=>1,
            "text"=>$text

        ];
        $arr=json_encode($arr,JSON_UNESCAPED_UNICODE);
        return $arr;
    }
    //返回正确结果
    public function error($text){
        $arr=[
            "msg"=>0,
            "text"=>$text

        ];
        return $arr;
    }
}
