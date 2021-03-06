<?php
include 'aliyun/aliyun-php-sdk-core/Config.php';
include 'aliyun/aliyun-php-sdk-push/Push/Request/V20160801/PushMessageToAndroidRequest.php';
use Push\Request\V20160801 as Push;

class RedBagSetupAction extends BaseAction {
	public function redbagtype(){
		$and = "";
		if ($_GET['typename']) {
			$and .= " and typename = '".$_GET['typename']."'";
		}
		import("ORG.Util.Page");
		$totalRows=M("redtype")->where("id is not null ".$and)->count();
		$page= new Page($totalRows,20);
		$this->list = M("redtype")->where("id is not null ".$and)->limit($page->firstRow,$page->listRows)->order("id desc")->select();
		$this->page= $page->show();
		$this->display();
	}
	
	public function editredbagtype(){
		if ($_POST['id']){
			$data['id'] = $_POST['id'];
			$data['condition'] = $_POST['condition'];
			$data['typename'] = $_POST['typename'];
			$data['price'] = $_POST['price'];
			$data['failtime'] = $_POST['failtime'];
			$is = M("redtype")->save($data);
			if ($is) {
				$data2['msg'] = 1;
			} else {
				$data2['msg'] = 0;
			}
		} else {
			$data['condition'] = $_POST['condition'];
			$data['typename'] = $_POST['typename'];
			$data['price'] = $_POST['price'];
			$data['failtime'] = $_POST['failtime'];
			$data['createtime'] = date("Y-m-d H:i:s");
			$is = M("redtype")->add($data);
			if ($is) {
				$data2['msg'] = 1;
			} else {
				$data2['msg'] = 0;
			}
		}
		echo json_encode($data2);
	}
	
	public function sendredbag() {
		$data['typeid'] = $_POST['id'];
		$data['createtime'] = date("Y-m-d H:i:s");
		$data['status'] = 0;
		$is = M("redbagmessage")->add($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		
		$this->pushpaymoneymessage();
		echo json_encode($data2);
	}
	
	//推送消息
	public function pushpaymoneymessage(){
		$type = 4;
		$content = "您有一个新的红包可领取！";
		$accessKeyId = C('accessKeyId');
		$accessKeySecret = C('accessKeySecret');
		$appKey = C('appKey');
		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $accessKeyId, $accessKeySecret);
		$client = new DefaultAcsClient($iClientProfile);
		$request = new Push\PushNoticeToiOSRequest();
		$request->setAppKey($appKey);
		$request->setTarget("DEVICE"); //推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALL: 推送给全部
		$request->setTargetValue("0deeb3edf17341fc8a982a615dc41326"); //根据Target来设定，如Target=DEVICE, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
		$request->setApnsEnv("DEV");//iOS的通知是通过APNS中心来发送的，需要填写对应的环境信息. DEV :表示开发环境, PRODUCT: 表示生产环境
		$request->setBody($content);
		$request->setExtParameters("{\"id\":\"".$id."\",\"type\":\"".$type."\",\"url\":\"".$url."\"}");
		$response = $client->getAcsResponse($request);
		print_r($response);
	}
	
	public function redbaginfo(){
		$and = "";
		if ($_GET['name']) {
			$and .= " and b.typename list '%".$_GET['name']."%'";
		}
		$list = M()->table("wt_redbagmessage a,wt_redtype b")->where("a.typeid = b.id ".$and)->field("a.*,b.typename,b.failtime")->order("createtime desc")->select();
		//应领人数和实领人数
		for ($i = 0; $i < count($list);$i++) {
			//应领人数
			$list[$i]['count'] = M("weixinuser")->count();
			$list[$i]['reality'] = M("redbag")->where("mid = ".$list[$i]['id'])->count();
		}
		$this->list = $list;
		$this->display();
	}
	
	public function delredbagmessage(){
		$data['id'] = $_POST['id'];
		$data['status'] = 1;
		$is = M("redbagmessage")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
}
?>