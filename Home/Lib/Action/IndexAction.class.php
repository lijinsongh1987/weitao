<?php

class IndexAction extends Action{
    public function index(){
    	$this->lunbo = M("shuff")->order("orderint asc")->select();
//     	echo M()->getLastSql();
        $list = M("goods")->where("status = 0 ")->limit(20)->order("update_time")->select();
        $tehuilist = M("goods")->where("tehuistatus = 1")->find();
        $this->quanlink = $tehuilist['Quan_link'];
        $this->id = $tehuilist['id'];
        $this->goodsid = $tehuilist['GoodsID'];
        $this->list = $list;
        $this->display();       
    }
    
    public function datajson(){
        $conter = $_GET['counter'];
        $conter = $conter*20;
        $data = M('goods')->limit($conter,20)->select();
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