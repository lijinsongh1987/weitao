<?php

class PersnoalAction extends Action{
    public function persnoal(){
        $this->display();       
    }
   
    //提现页面
    public function withdraw(){
        $this->display();
    }
    
    public function message(){
//     	$openid = $_POST['openid'];
    	$openid = $_POST['openid'];
    	//普通消息
    	$list = M("paymoneymessage")->where("openid = '".$openid."'")->select();
    	//红包消息
    	//查询推送中的红包
    	$redlist = M("redbagmessage")->where("status = 0")->select();
    	//查询自己有没有没取的红包
    	$count = 0;
    	for ($i = 0;$i < count($redlist);$i++) {
    		$mlist = M("redbag")->where("mid = ".$redlist[$i]['id'])->select();
    		//查询该红包详情
    		$infolist = M("redtype")->where("id = ".$redlist[$i]['typeid'])->select();
    		$data[$count]['content'] = "您有一个价值".$infolist[0]['price']."元红包可领取！";
    		$data[$count]['createtime'] = "失效日期：".$infolist[0]['failtime'];
    		$data[$count]['typeid'] = $infolist[0]['id'];
    		$data[$count]['mid'] = $redlist[$i]['id'];
    		if (!$mlist) {
    			$data[$count]['status'] = 0;
    		} else {
    			$data[$count]['status'] = 1;
     		}
    		$count++;
    	}
    	$this->list = $data;
    	$this->mlist = $list;
        $this->display();
    }
    
    public function invite(){
        $this->display();
    }
    
    public function classroom(){
        
        $this->list=M('classroom')->select();
        $this->display();
    }
    
    public function textcontent(){
        $id = $_GET['id'];
        $list=M('classroom')->where("id = '".$id."'")->find();
        $this->content = $list['conten'];
        $this->display();
    }
    
    public function setup(){
        $this->display();
    }
    
    public function recording(){
        $this->display();
    }
    
    public function coupon(){
        //$openid = $_POST['openid'];
    	$openid = $_GET['openid'];
        $dates = strtotime(date("Y-m-d"));
        $list=M()->table("wt_redbag a,wt_redtype b")->where("a.openid = '".$openid."' and a.tid = b.id")->field("a.id as redid,a.*,b.*")->select();
        for($i = 0;$i < count($list);$i++){
            if(!strtotime($list[$i]['faildate']) == 0){
                if($dates > strtotime($list[$i]['faildate'])){
                    $list[$i]['shixiao'] = 1;
                }else{
                    $list[$i]['shixiao'] = 0;
                }
            }else{
                $list[$i]['shixiao'] = 0;
            }
        }
        $this->list = $list;
        $this->display();
    }
    
    public function proxy(){
        $this->display();
    }
    
    public function fillIn(){
        $this->display();
    }
    
    public function getyue(){
    	$openid = $_POST['openid'];
    	//��ȡ���
    	$yue = M("yue")->where("openid = '".$openid."'")->select();
    	//��������
    	$fen = M("yuesource")->where("openid = '".$openid."' and status = 1")->field("sum(price) as price")->group("openid")->select();
    	//��������
    	$yao = M("yuesource")->where("openid = '".$openid."' and status = 0")->field("sum(price) as price")->group("openid")->select();
    	//�������
    	$red = M("redbag")->where("openid = '".$openid."' and status = 0")->count();
    	if ($yue){
    		$data['yue'] = $yue[0]['yue'];
    	} else {
    		$data['yue'] = 0;
    	}
    	if ($fen){
    		$data['fen'] = $fen[0]['price'];
    	} else {
    		$data['fen'] = 0;
    	}
    	if ($yao){
    		$data['yao'] = $yao[0]['price'];
    	} else {
    		$data['yao'] = 0;
    	}
    	if ($red){
    		$data['red'] = $red[0]['price'];
    	} else {
    		$data['red'] = 0;
    	}
    	echo json_encode($data);
    }
    
    public function getyueinfo(){
    	//查询余额
    	$yuelist = M("yue")->where("openid = '".$_POST['openid']."'")->select();
    	//查询支付宝帐号
    	$alilist = M("weixinuser")->where("openid = '".$_POST['openid']."'")->select();
    	$data['yue'] = $yuelist;
    	$data['ali'] = $alilist;
    	echo json_encode($data);
    }
    
    public function addtixian(){
    	$data['openid'] = $_POST['openid'];
    	$data['tixian'] = $_POST['tixian'];
    	$data['alipay'] = $_POST['alipay'];
    	$data['realname'] = $_POST['realname'];
    	$data['time'] = date("Y-m-d H:i:s");
    	$data['status'] = 0;
    	$is = M("tixian")->add($data);
    	//余额减去提现金额
    	$yuelist = M("yue")->where("openid = '".$_POST['openid']."'")->select();
    	$data3['yue'] = $yuelist[0]['yue'] - $_POST['tixian'];
    	$data3['id'] = $yuelist[0]['id'];
    	$is = M("yue")->save($data3);
    	//给用户添加帐号和真实姓名
    	$weixinuserlist = M("weixinuser")->where("openid = '".$_POST['openid']."'")->find();
    	$data4['id'] = $weixinuserlist['id'];
    	$data4['alipay'] = $_POST['alipay'];
    	$data4['realname'] =$_POST['realname'];
    	M("weixinuser")->save($data4);
    	if ($is) {
    		$data2['msg'] = 1;
    	} else {
    		$data2['msg'] = 0;
    	}
    	echo json_encode($data2);
    }
    
    public function getpayrecord(){
    	$conter = $_POST['counter'];
    	$conter = $conter*20;
    	$openid = $_POST['openid'];
    	$data = M('tixian')->where("openid = '".$_POST['openid']."'")->limit($conter,20)->order("time desc")->select();
//     	$data['sql'] = M()->getLastSql();
    	echo json_encode($data);
    }
    
    public function share(){
        $this->display();
    }
    
    public function sharegoodslist(){
        
        
        $conter = $_GET['counter'];
        $conter = $conter*20;
        $data = M("goods")->where("id is not null and tuistatus = 1 and status = 0 ".$and)->limit($conter,20)->select();
        //$data['sql'] = M()->getLastSql();
        echo json_encode($data);
    }
    
    public function getredbag(){
    	$openid = $_POST['openid'];
    	$dates = strtotime(date("Y-m-d"));
    	$list=M()->table("wt_redbag a,wt_redtype b")->where("a.openid = '".$openid."' and a.tid = b.id")->field("a.id as redid,a.*,b.*")->select();
    	for($i = 0;$i < count($list);$i++){
    		if(!strtotime($list[$i]['faildate']) == 0){
    			if($dates > strtotime($list[$i]['faildate'])){
    				$list[$i]['shixiao'] = 1;
    			}else{
    				$list[$i]['shixiao'] = 0;
    			}
    		}else{
    			$list[$i]['shixiao'] = 0;
    		}
    	}
    	echo json_encode($list);
    }
    
    public function getweixinuserunder(){
    	$openid = $_POST['openid'];
    	$list = M("weixinuser")->where("leadid = '".$openid."'")->select();
    	$data['count'] = count($list);
    	$data['data'] = $list;
    	echo json_encode($data);
    }
    
    public function pickupredbag(){
    	$list = M("redbag")->where("openid = '".$_POST['openid']."' and mid = ".$_POST['mid'])->select();
    	//判断是否有重复领取
    	if (!$list) {
    		$typelist = M("redtype")->where("id = ".$_POST['id'])->find();
    		$now = date("Y-m-d H:i:s");
    		$shixiaotime = $typelist['failtime']." 23:59:59";
    		//判断红包是否失效
    		if (strtotime($now) < strtotime($shixiaotime)) {
	    		$data['openid'] = $_POST['openid'];
	    		$data['tid'] = $typelist['id'];
	    		$data['faildate'] = $typelist['failtime'];
	    		$data['status'] = 0;
	    		$data['mid'] = $_POST['mid'];
	    		$data['createtime'] = date("Y-m-d H:i:s");
	    		$is = M("redbag")->add($data);
	    		if ($is) {
	    			$data2['msg'] = "领取成功！";
	    			$data2['success'] = 1;
	    			$data2['mid'] = $_POST['mid'];
	    		} else {
	    			$data2['msg'] = "领取失败！";
	    		}
    		} else {
    			$data2['msg'] = "该红包已过有效期，无法领取！";
    		}
    	} else {
    		$data2['msg'] = "该红包无法重复领取！";
    	}
    	echo json_encode($data2);
    }
    
    //提交订单获取红包
    public function submitorder(){
    	$orderid = $_POST['orderid'];
    	//判断红包是否能提交，红包提交条件提交订单号为订单结算状态
    	$list = M("order")->where("order_id = '".$orderid."' and order_status = '订单结算'")->select();
    	if ($list) {
    		//查询到输入订单号为结算状态就作废当前红包，并往余额里面添加红包额度
    		$data3['id'] = $_POST['redid'];
    		$data3['status'] = 1;
    		$is = M("redbag")->save($data3);
    		//先查询余额，再添加余额
    		$yuelist = M("yue")->where("openid = '".$_POST['openid']."'")->select();
    		$data2['yue'] = $yuelist[0]['yue'] + $_POST['price'];
    		$data2['id'] = $yuelist[0]['id'];
    		$flag = M("yue")->save($data2);
    		//添加一条个人收入记录
    		$data4['price'] = $_POST['price'];
    		$data4['openid'] = $_POST['openid'];
    		$data4['content'] = "领取价值".$_POST['price']."元红包一个";
    		$data4['time'] = date("Y-m-d H:i:s");
    		$data4['status'] = 0;
    		$fa = M("yuesource")->add($data4);
    		if ($is && $flag && $fa) {
    			$data['msg'] = "红包领取成功，请前往个人后台查看！";
    		} else {
    			$data['msg'] = "红包领取失败，请确认签收后再来领取！";
    		}
    	} else {
    		$data['msg'] = "您输入的订单号不正确或没有签收！";
    	}
    	echo json_encode($data);
    }
}

?>