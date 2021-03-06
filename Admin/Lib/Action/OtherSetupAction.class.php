<?php 
include 'aliyun/aliyun-php-sdk-core/Config.php';
include 'aliyun/aliyun-php-sdk-push/Push/Request/V20160801/PushMessageToAndroidRequest.php';
use Push\Request\V20160801 as Push;

class OtherSetupAction extends BaseAction{
    //知识课堂
    public function classroom(){
        $this->type = M("texttype")->select();
        
        if ($_GET['texttitle']) {
            $and .=" and a.title = '".$_GET['texttitle']."'";
        }
        if ($_GET['texttypesubmit']) {
            $and .=" and a.type = '".$_GET['texttypesubmit']."'";
        }
        import("ORG.Util.Page");
        $totalRows=M()->table("wt_classroom a,wt_texttype b,wt_user c")->where("a.type = b.id and a.userid = c.id ".$and)->count();
        $page= new Page($totalRows,20);
        $this->list = M()->table("wt_classroom a,wt_texttype b,wt_user c")->where("a.type = b.id and a.userid = c.id")->limit($page->firstRow,$page->listRows)->field("a.*,b.typename,c.realname")->order("time desc")->select();
//         echo M()->getLastSql();
        $this->page= $page->show();
        $this->display();
    }
    public function addtext(){
    	import('ORG.Net.UploadFile');
    	$upload = new UploadFile();
    	$upload->maxSize  = 3145728 ;
    	$upload->allowExts  = array('jpg', 'png', 'jpeg');
    	$upload->savePath =  './textimg/';
    	$upload->upload();
    	$info = $upload->getUploadFileInfo();
    	if ($info[0]['savename']) {
    		$data['imgurl'] = './textimg/'.$info[0]['savename'];
    	}
        if ($_POST['id']) {
            $data['id'] = $_POST['id'];
            $data['title'] = $_POST['title'];
            $data['type'] = $_POST['type'];
            $data['conten'] = $_POST['editor'];
            $is = M("classroom")->save($data);
        } else {
            $data['title'] = $_POST['title'];
            $data['type'] = $_POST['type'];
            $data['userid'] = $_SESSION['user'][0]['id'];
            $data['time'] = date("Y-m-d H:i:s");
            $data['conten'] = $_POST['editor'];
            $is = M("classroom")->add($data);
        }
        $this->redirect("OtherSetup/classroom");
    }
    public function getclassroom(){
        $id = $_GET['id'];
        $list = M("classroom")->where("id = ".$id)->select();
        echo json_encode($list);
    }
    
    public function delclassroom(){
        $is = M("classroom")->where("id = ".$_GET['id'])->delete();
        if ($is) {
            $data['msg'] = 1;
        } else {
            $data['msg'] = 0;
        }
        echo json_encode($data);
    }
    
    //文章添加
    public function addconten(){
        if ($_GET['id']) {
            $this->classlist = M('classroom')->find($_GET['id']);
        }
        $this->type = M("texttype")->select();
        $this->display();
    }
    
    
    public function messageset(){
    	if ($_GET['title']) {
    		$and .=" and title = '".$_GET['title']."'";
    	}
    	import("ORG.Util.Page");
    	$totalRows=M("message")->where("id is not null ".$and)->count();
    	$page= new Page($totalRows,20);
    	$this->list = M("message")->where("id is not null ".$and)->limit($page->firstRow,$page->listRows)->order("id desc")->select();
    	//         echo M()->getLastSql();
    	$this->page= $page->show();
    	$this->display();
    }
    
    public function editmessage(){
    	if ($_POST['id']) {
    		$data['id'] = $_POST['id'];
    		$data['title'] = $_POST['title'];
    		$data['orid'] = $_POST['orid'];
    		$data['content'] = $_POST['content'];
    		$data['typeid'] = $_POST['typeid'];
    		$is = M("message")->save($data);
    		if ($is) {
    			$data2['msg'] = 1;
    		} else {
    			$data2['msg'] = 2;
    		}
    	} else {
    		$data['title'] = $_POST['title'];
    		$data['orid'] = $_POST['orid'];
    		$data['content'] = $_POST['content'];
    		$data['typeid'] = $_POST['typeid'];
    		$data['createtime'] = date("Y-m-d");
    		$is = M("message")->add($data);
    		if ($is) {
    			$data2['msg'] = 1;
    		} else {
    			$data2['msg'] = 2;
    		}
    	}
    	echo json_encode($data2);
    }
    
    public function delmessage(){
    	$id = $_POST['id'];
    	$is = M("message")->where("id = ".$id)->delete();
    	if ($is) {
    		$data2['msg'] = 1;
    	} else {
    		$data2['msg'] = 2;
    	}
    	echo json_encode($data2);
    }
    
    public function pushmessage(){
    	$id = $_POST['orid'];
    	$type = $_POST['typeid'];
    	$content = $_POST['content'];
    	$url = "";
    	//获取商品信息
    	if ($type == 1) {
    		$goodslist = M("goods")->where("id = ".$id)->select();
    		$data = $this->getgourllink($goodslist[0]['Quan_link'],$id,$goodslist[0]['GoodsID']);
    		$url = $data['gourllink'];
    	}
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
    	
    	$data3['id'] = $_POST['id'];
    	$data3['status'] = 1;
    	M("message")->save($data3); 
    	if ($response) {
    		$data2['msg'] = 1;
    	} else {
    		$data2['msg'] = 2;
    	}
    	echo json_encode($data2);
    }
    
    public function getgourllink ($quanlink,$id,$goodsid) {
    	$urllist = M("url")->field("status")->find();
    	$data['jumpstatus'] = $urllist['status'];
    	if ($data['jumpstatus'] == 0) {
    		//转锟斤拷锟斤拷锟斤拷534150603181
    		$pid = "mm_30979406_3042389_10494070";
    		$pidlist = split("_",$pid);
    		//锟斤拷询锟斤拷应锟斤拷tokenid
    		$tokenid = M("account")->where("member_id = '".$pidlist[1]."'")->find();
    		$url = "http://tbapi.00o.cn/highapi.php";
    		$linklist = array();
    		$linklist =  split("&",$quanlink);
    		$linklist[1] = str_replace("activity_id", "activityId", $linklist[1]);
    		$post_data = array ("item_id" => $goodsid,"adzone_id" => "10494070","platform"=>"1","site_id"=>"3042389","token"=>$tokenid['access_token']);
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		// post锟斤拷锟斤拷
    		curl_setopt($ch, CURLOPT_POST, 1);
    		// post锟侥憋拷锟斤拷
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    		$output = array();
    		$output = curl_exec($ch);
    		curl_close($ch);
    		$output_array = json_decode($output,true);
    		$data['gourllink'] = $output_array['result']['data']['coupon_click_url']."&".$linklist[1];
    	}
    	return $data;
    }
    public function indexshuff(){
    	$and = "";
    	if ($_GET['title']){
    		$and = "title like '%".$_GET['title']."%'";
    	}
    	$this->list = M("shuff")->where($and)->select();
    	$this->display();
    }
    public function addshuff(){
    	import('ORG.Net.UploadFile');
    	$upload = new UploadFile();
    	$upload->maxSize  = 3145728 ;
    	$upload->allowExts  = array('jpg', 'png', 'jpeg');
    	$upload->savePath =  './shuffimage/';
    	$upload->upload();
    	$info = $upload->getUploadFileInfo();
    	if ($info[0]['savename']) {
    		$data['imageurl'] = './shuffimage/'.$info[0]['savename'];
    	}
    	if ($_POST['id']) {
    		$data['id'] = $_POST['id'];
    		$data['title'] = $_POST['titleone'];
    		$data['httpurl'] = $_POST['httpurl'];
    		$data['orderint'] = $_POST['orderint'];
    		$is = M("shuff")->save($data);
    	} else {
    		$data['id'] = $_POST['id'];
    		$data['title'] = $_POST['titleone'];
    		$data['httpurl'] = $_POST['httpurl'];
    		$data['orderint'] = $_POST['orderint'];
    		$data['createtime'] = date("Y-m-d H:i:s");
    		$is = M("shuff")->add($data);
    	}
    	$this->redirect("OtherSetup/indexshuff");
    }
    
    public function delshuff(){
    	$id = $_POST['id'];
    	$is = M("shuff")->where("id = ".$id)->delete();
    	if ($is) {
    		$data['msg'] = 1;
    	} else {
    		$data['msg'] = 0;
    	}
    	echo json_encode($data);
    }
}
?>