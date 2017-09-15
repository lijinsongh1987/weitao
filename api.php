<?php
class ApiAction {
	public function weixinapi (){
		$data['nickname'] = $_GET['nickname'];
		$data['imageurl'] = $_GET['imageurl'];
		$list = M("weixinuser")->where("openid = '".$_GET['openid']."'")->select();
		if ($list) {
			$is = M("weixinuser")->where("openid = '".$_GET['openid']."'")->save($data);
		} else {
			$data['openid'] = $_GET['openid'];
			$is = M("weixinuser")->add($data);
		}
		if ($is) {
			echo "success";
		} else {
			echo "fail";
		}
	}
}