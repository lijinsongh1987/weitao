<?php

class LoginAction extends Action{
    public function  login(){
        $this->display();
    }
    public function dologin(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = M('user');
        $where['username'] = $username;
        $where['password'] = $password;
        $arr = $user->where("username = '".$username."' and password = '".$password."'")->select();
        if($arr){
            //鑾峰彇鏈儴闂ㄦ墍鏈夌敤鎴稩D
            session('username',$username);
            session(C('USER_AUTH_KEY'),$arr['id']);
            session("user",$arr);
            session("realname",$arr[0]['realname']);
            $this->redirect("Index/index");
        }else{
            session("usermsg",$user->getLastSql());
            $this->redirect("Login/login");
        }
    }
    //閫�嚭鐧诲綍
    //閫�嚭
    public function logoff()
    {
        unset($_SESSION);
        session_destroy();
        $this->redirect('Login/login');
    }
    
    
    public function modify(){
        $this->display();
    }
    public function modifyadminpassword(){
            $password = $_POST['adminpassword'];
            $adminnewpassword= $_POST['adminnewpassword'];
            $username= $_POST['username'];
            $is = M('user')->where("password = '".$password."' and username = '".$username."'")-> select();
            if(!$is){
                $data['msg'] = 0;
                $this->ajaxReturn($data);
            }else{
                $data['password'] = $_POST['adminnewpassword'];
                $judgment= M('user')->where("password = '".$password."' and username = '".$username."'")->save($data);
                if(!$judgment){
                    $data['msg'] = 1;
                }else{
                    $data['msg'] = 2;
                } 
            }
            $this->ajaxReturn($data);
    }
}