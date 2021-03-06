<?php

class NewsAction extends Action{
    public function news(){
        $this -> news = M('caiji')->where("status = 1")->limit(20)->select();
        $this->display();       
    }
   
    
    public function newscontent(){
        $id = $_GET['id'];
        $news = M('caiji')->where("id = $id")->order("shixiaotime desc")->select();
        //带用户openid阅读数
        if ($_GET['openid']) {
        	$list = M("read")->where("rid = ".$_GET['id']." and openid = '".$_GET['openid']."'")->select();
        	if ($list) {
        		if ($list[0]['readnumber'] < 20) {
        			$data['id'] = $list[0]['id'];
        			$data['readnumber'] = $list[0]['readnumber'] + 1;
        			$data['readtime'] = date("Y-m-d H:i:s");
        			M("read")->save($data);
        		}
        	} else {
        		$data['rid'] = $_GET['id'];
        		$data['openid'] = $_GET['openid'];
        		$data['readnumber'] = 1;
        		$data['readtime'] = date("Y-m-d H:i:s");
        		M("read")->add($data);
        	}
        	//阅读数小于20的话添加一次余额,添加一次yuesource,单价为表wt_dictionary字段ReadPrice的值
        	if ($list[0]['readnumber'] < 20) {
        		$readlist = M("dictionary")->where("field = 'ReadPrice'")->select();
        		$yuelist = M("yue")->where("openid = '".$_GET['openid']."'")->select();
        		$data2['id'] = $yuelist[0]['id'];
        		$data2['yue'] = $yuelist[0]['yue'] + $readlist[0]['value'];
        		M("yue")->save($data2);
        		$data3['price'] = $readlist[0]['value'];
        		$data3['openid'] = $_GET['openid'];
        		$data3['content'] = '阅读量一次'.$readlist[0]['value'].'元';
        		$data3['time'] = date("Y-m-d H:i:s");
        		$data3['status'] = 1;
        		M("yuesource")->add($data3);
        	}
        //不带用户openid阅读数
        } else {
        	$list = M("read")->where("rid = ".$_GET['id']." and openid is null")->select();
        	//�ж�����ӻ����޸�
        	if ($list) {
        		//�޸�
        		$data['id'] = $list[0]['id'];
        		$data['readnumber'] = $list[0]['readnumber'] + 1;
        		$data['readtime'] = date("Y-m-d H:i:s");
        		M("read")->save($data);
        	} else {
        		//���
        		$data['rid'] = $_GET['id'];
        		$data['readnumber'] = 1;
        		$data['readtime'] = date("Y-m-d H:i:s");
        		M("read")->add($data);
        	}
        }
        $this->newslist = $news[0]['content'];
        $this->display();
    }
    
    public function datajson(){
        $conter = $_GET['counter'];
        $conter = $conter*20;
        $data = M('caiji')->where("status = 1")->limit($conter,20)->order("shixiaotime desc")->select();
        //$data['sql'] = M()->getLastSql();
        echo json_encode($data);
    }
    
    public function changeurl(){
    	$quanlink = $_GET['quanlink'];
    	$id = $_GET['id'];
    	$goodsid = $_GET['goodsid'];
    	$data = $this->getgourllink($quanlink,$id,$goodsid);
    	echo json_encode($data);
    }
    
    public function getgourllink ($quanlink,$id,$goodsid) {
    	$urllist = M("url")->field("status")->find();
    	$data['jumpstatus'] = $urllist['status'];
    	if ($data['jumpstatus'] == 0) {
    		//ת������534150603181
    		$pid = "mm_30979406_3042389_10494070";
    		$pidlist = split("_",$pid);
    		//��ѯ��Ӧ��tokenid
    		$tokenid = M("account")->where("member_id = '".$pidlist[1]."'")->find();
    		$url = "http://tbapi.00o.cn/highapi.php";
    		$linklist = array();
    		$linklist =  split("&",$quanlink);
    		$linklist[1] = str_replace("activity_id", "activityId", $linklist[1]);
    		$post_data = array ("item_id" => $goodsid,"adzone_id" => "10494070","platform"=>"1","site_id"=>"3042389","token"=>$tokenid['access_token']);
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		// post����
    		curl_setopt($ch, CURLOPT_POST, 1);
    		// post�ı���
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    		$output = array();
    		$output = curl_exec($ch);
    		curl_close($ch);
    		$output_array = json_decode($output,true);
    		$data['gourllink'] = $output_array['result']['data']['coupon_click_url']."&".$linklist[1];
    	}
    	return $data;
    }
}

?>