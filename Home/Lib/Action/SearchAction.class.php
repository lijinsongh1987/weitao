<?php

class SearchAction extends Action{
    public function search(){
        $this->count = M('goods')->count();
        $this->list=M("goods")->where("id is not null ".$and)->order("update_time desc")->limit(3)->select();
        $this->display();       
    }
    
    public function searchcontent(){
        $this ->key = $_GET['key'];
        $this->display();
        
    }
    
    public function searchcontentselect(){
        
        $conter = $_GET['counter'];
        $conter = $conter*20;
        if($_GET['key']){
            $and .= " and Title like '%".$_GET['key']."%'";
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
    	if ($data['jumpstatus'] == 0){
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
	    	$data['gourllink'] = $output_array['result']['data']['coupon_click_url']."&".$linklist[1];
    	}
    	return $data;
    }
}

?>