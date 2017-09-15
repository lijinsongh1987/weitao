<?php

class InviteAction extends Action{
    public function invite(){
    	$list = M("weixinuser")->where("openid = '".$_GET['openid']."'")->select();
    	$str = M("redtype")->where("id = 2")->select();
    	$this->price = $str[0]['price'];
    	$this->nickname = $list[0]['nickname'];
    	$this->imageurl = $list[0]['imageurl'];
    	$this->openid = $_GET['openid'];
        $this->display();
    }
}

?>