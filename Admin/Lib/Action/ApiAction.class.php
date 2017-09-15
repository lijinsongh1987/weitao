<?php
include 'rongcloud.php';

class ApiAction {
	public function index(){
		$data['nickname'] = $_GET['nickname'];
		$data['imageurl'] = $_GET['imageurl'];
		$list = M("weixinuser")->where("openid = '".$_GET['openid']."'")->select();
		if ($list) {
			$is = M("weixinuser")->where("openid = '".$_GET['openid']."'")->save($data);
		} else {
			$data['openid'] = $_GET['openid'];
			$is = M("weixinuser")->add($data);
		}
		echo "success";
	}
	
	
	public function getgoodsinfo(){
		$id = $_GET['id'];
		$list = M("goods")->where("id = ".$id)->select();
		echo json_encode($list[0]);
	}
	
	public function test() {
		$str = $this->getgourllink ("http://shop.m.taobao.com/shop/coupon.htm?seller_id=2889364447&activity_id=9835decfd3344cc0a41fd33ba455fa8d","57034","534150603181");
		print_r($str);
	}
	public function getgourllink ($quanlink,$id,$goodsid) {
		//ת������534150603181
		$pid = "mm_30979406_3042389_10494070";
		$pidlist = split("_",$pid);
		//��ѯ��Ӧ��tokenid
		$tokenid = M("account")->where("member_id = '".$pidlist[1]."'")->find();
		$url = "http://tbapi.00o.cn/highapi.php";
		$linklist = array();
		$linklist =  split("&",$quanlink);
		$linklist[1] = str_replace("activity_id", "activityId", $linklist[1]);
		$post_data = array ("item_id" => "534150603181","adzone_id" => "10494070","platform"=>"1","site_id"=>"3042389","token"=>$tokenid['access_token']);
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
		return $data;
	}
	
	public function getalipaycallback (){
		$request = $_GET['alipay_fund_trans_toaccount_transfer_response'];
		if ($request['code'] == "10000"){
			$data['bizno'] = $request['out_biz_no'];
			$data['orderid'] = $request['order_id'];
			$data['paytime'] = $request['pay_date'];
			file_put_contens("./log.txt",$request['sub_msg']);
			echo "success";
		} else {
			file_put_contens("./log.txt",$request['sub_msg']);
			echo "fail";
		}
	}
	
	public function savetbuser(){
		$data['nickname'] = $_POST['nickname'];
		$data['imageurl'] = $_POST['imageurl'];
		$data['openid'] = $_POST['openid'];
		$data['leadid'] = $_POST['leadid'];
		$data['createtime'] = date("Y-m-d H:i:s");
		$list = M("weixinuser")->where("openid = '".$_POST['openid']."'")->select();
		$is = false;
		if (!$list) {
			if ($data['nickname'] && $data['openid'] && $data['imageurl']) {
				$is = M("weixinuser")->add($data);
			}
			$list = M("weixinuser")->where("id = ".C("red_xishouid"))->select();
			$user = M("weixinuser")->where("openid = '".$_POST['leadid']."'")->select();
			$data6['yaoqingnumber'] = $user[0]['yaoqingnumber'] + 1;
			$data6['id'] = $user[0]['id'];
			M("weixinuser")->save($data6);
			//插入余额
			$yuelist = M("yue")->where("openid = '".$_POST['leadid']."'")->select();
			if($yuelist){
				$data4['id'] = $yuelist[0]['id'];
				$data4['yue'] = $yuelist[0]['yue'] + 2;
				M("yue")->save($data4);
			} else {
				$data4['yue'] = 2;
				$data4['openid'] = $_POST['leadid'];
				M("yue")->add($data4);
			}
			//往来源表插值
			$data5['price'] = 2;
			$data5['openid'] = $_POST['leadid'];
			$data5['content'] = "邀请用户注册送2元红包";
			$data5['time'] = date("Y-m-d H:i:s");
			$data5['status'] = 0;//0邀请，1分享，2红包，3代理
			M("yuesource")->add($data5);
		}
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
	
	/**
	 * 获取默认pid
	 */
	public function get_default_pid(){
		$list = M("dictionary")->where("field = 'CPid'")->select();
		echo json_encode($list[0]);
	}
	
	//获取是否有领取新手红包并返回新手红包信息
	public function xinredbag(){
		$openid = $_POST['openid'];
		$list = M()->table("wt_redtype a,wt_redbag b")->where("a.id = b.tid and b.openid = '".$openid."' and b.tid = 2")->select();
		$red = M("redtype")->where("id = 2")->select();
		$data['data'] = $red[0]['price'];
		if ($list) {
			$data['msg'] = 1;
		} else {
			$data['msg'] = 0;
		}
		echo json_encode($data);
	}
	//领取新手红包
	public function insertredbag(){
		$data['openid'] = $_POST['openid'];
		//查询是否有领取新手红包
		$xinlist = M("redbag")->where("openid = '".$_POST['openid']."' and tid = 2")->select();
		if (!$xinlist) {
			$list = M("redtype")->where("id = 2")->find();
			$data['tid'] = 2;
			$data['createtime'] = date("Y-m-d H:i:s");
			$data['status'] = 0;
			$data['faildate'] = $list['faildate'];
			$is = M("redbag") -> add($data);
			if ($list) {
				$data['msg'] = 1;
			} else {
				$data['msg'] = 0;
			}
		} else {
			$data['msg'] = 2;
		}
		
		echo json_encode($data);
	}
	
	public function getToken(){
		$appKey = '8luwapkv8r5pl';
		$appSecret = 'TNY4ryTolQjUX';
		$RongCloud = new RongCloud($appKey,$appSecret);
		$openid = $_POST['openid'];
		$nickname = $_POST['nickname'];
		$imageurl = $_POST['imageurl'];
		$result = $RongCloud->user()->getToken($openid,$nickname,$imageurl);
		echo json_encode(json_decode($result,true));
	}
}
?>