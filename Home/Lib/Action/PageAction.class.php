<?php

class PageAction extends Action{
    public function page(){
        $this -> types = $_GET['types'];
        $this->typelist=M("goodstype")->select();
        $this->display();       
    }
   
    
    public function searchpage(){
    	$this -> types = $_GET['types'];
    	$this->display();
    }
    
    public function searchgoodslist(){
    	$conter = $_GET['counter'];
    	$conter = $conter*20;
    	if($_GET['types'] == 4){
    		$and .=" and Cid = 4";
    	}
    	if($_GET['types'] == 3){
    		$and .=" and Cid = 3";
    	}
    	if($_GET['types'] == 6){
    		$and .=" and Cid = 6";
    	}
    	if($_GET['types'] == 2){
    		$and .=" and Cid = 2";
    	}
    	if($_GET['types'] == 8){
    		$and .=" and Cid = 8";
    	}
    	if($_GET['types'] == 5){
    		$and .=" and Cid = 5";
    	}
    	$data = M("goods")->where("id is not null ".$and)->order($order)->limit($conter,20)->select();
    	//$data['sql'] = M()->getLastSql();
    	echo json_encode($data);
    }
    
    public function goodslist(){
        
        
        $conter = $_GET['counter'];
        $conter = $conter*20;
        if($_GET['types'] == 1){
            //浼樻儬鍒哥被鐩�
        }
        if($_GET['types'] == 2){
            $and .=" and Price <= 9.9";
            //9.9绫荤洰
        }
        if($_GET['types'] == 3){
        	$and .=" and Price <= 9.9";
        }
        
        if($_GET['filter'] == 1){
            $order = "Sales_num desc";
        }else if($_GET['filter'] == 2){
            $order = "Price asc";
        }else if($_GET['filter'] == 3){
            $order = "Quan_price desc";
        }else{
            $order = "update_time desc";
        }
        if($_GET['id'] != 0){
            $and .=" and Cid = '".$_GET['id']."'";
        }else{
            $and .=" and Cid != 0";
        }
        $data = M("goods")->where("id is not null ".$and)->order($order)->limit($conter,20)->select();
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
	    	//转换链接
	    	$pid = "mm_30979406_3042389_10494070";
	    	$pidlist = split("_",$pid);
	    	//查询对应的tokenid
	    	$tokenid = M("account")->where("member_id = '".$pidlist[1]."'")->find();
	    	$url = "http://tbapi.00o.cn/highapi.php";
	    	$linklist = array();
	    	$linklist =  split("&",$quanlink);
	    	$linklist[1] = str_replace("activity_id", "activityId", $linklist[1]);
	    	$post_data = array ("item_id" => $goodsid,"adzone_id" => "10494070","platform"=>"1","site_id"=>"3042389","token"=>$tokenid['access_token']);
	    	$ch = curl_init();
	    	curl_setopt($ch, CURLOPT_URL, $url);
	    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    	// post数据
	    	curl_setopt($ch, CURLOPT_POST, 1);
	    	// post的变量
	    	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	    	$output = array();
	    	$output = curl_exec($ch);
	    	curl_close($ch);
	    	$output_array = json_decode($output,true);
	    	$data['link'] = $output_array['result']['data']['coupon_click_url']."&".$linklist[1];
    	}
    	return $data;
    }
}

?>