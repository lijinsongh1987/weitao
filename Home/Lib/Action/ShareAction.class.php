


<?php

class ShareAction extends Action{
    public function share(){
        $goodsid = $_GET['id'];
        $goodslist=M("goods")->where("id = '".$goodsid."'")->find();
        $url = M("url")->select();
        if ($url[0]['status'] == 0) {
        	$goodslist['url'] = urlencode($this->getgourllink($goodslist['Quan_link'], $goodslist['id'], $goodslist['GoodsID'])."&openid=".$_GET['openid']);
        } else if ($url[0]['status'] == 1) {
        	$goodslist['url'] = " &id=".$_GET['id']."&openid=".$_GET['openid'];
        }
        $this-> goodsimg = $goodslist['Pic'];
        $this-> IsTmall= $goodslist['IsTmall'];
        $this-> D_title= $goodslist['D_title'];
        $this-> Org_Price= $goodslist['Org_Price'];
        $this-> Quan_receive= $goodslist['Quan_receive'];
        $this-> Price= $goodslist['Price'];
        $this-> Quan_price= $goodslist['Quan_price'];
        //$this->url = urlencode ($goodslist['url']);
        $this->url = $goodslist['url'];
        //echo M()->getLastSql();
        $this->display();       
    }
    public function getgourllink ($quanlink,$id,$goodsid) {
    	//
    	$pid = "mm_30979406_3042389_10494070";
    	$pidlist = split("_",$pid);
    	//
    	$tokenid = M("account")->where("member_id = '".$pidlist[1]."'")->find();
    	$url = "http://tbapi.00o.cn/highapi.php";
    	$linklist = array();
    	$linklist =  split("&",$quanlink);
    	$linklist[1] = str_replace("activity_id", "activityId", $linklist[1]);
    	$post_data = array ("item_id" => $goodsid,"adzone_id" => "10494070","platform"=>"1","site_id"=>"3042389","token"=>$tokenid['access_token']);
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	// 
    	curl_setopt($ch, CURLOPT_POST, 1);
    	//
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    	$output = array();
    	$output = curl_exec($ch);
    	curl_close($ch);
    	$output_array = json_decode($output,true);
    	$data = $output_array['result']['data']['coupon_click_url']."&".$linklist[1];
    	return $data;
    }
   
}

?>