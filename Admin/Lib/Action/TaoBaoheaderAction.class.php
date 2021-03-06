
<?php
require './vendor/autoload.php';
use QL\QueryList;
class TaoBaoheaderAction extends BaseAction{
	public function articleget(){
		$this->headertype = M("headertype")->select();
		$this->display();
	}
	
	public function headertype(){
		if ($_GET['name']) {
			$and .= " and typename = '".$_GET['name']."'";
		}
		$this->list = M("headertype")->where("id is not null ".$and)->select();
		$this->display();
	}
	
	public function editpartment(){
		if ($_POST['id']) {
			$data['id'] = $_POST['id'];
			$data['typename'] = $_POST['typename'];
			$data['columnid'] = $_POST['columnid'];
			$data['createtime'] = date("Y-m-d");
			$is = M("headertype")->save($data);
			if ($is) {
				$data2['msg'] = 1;
			} else {
				$data2['msg'] = 2;
			}
		} else {
			$data['columnid'] = $_POST['columnid'];
			$data['typename'] = $_POST['typename'];
			$data['createtime'] = date("Y-m-d");
			$is = M("headertype")->add($data);
			if ($is) {
				$data2['msg'] = 1;
			} else {
				$data2['msg'] = 2;
			}
		}
		echo json_encode($data2);
	}
	
	public function caijishow(){
		$type = $_GET['headertype'];
		$number = $_GET['number'];
		$_SESSION['page'] = 0;
		//$url = C("caijiurl")."?columnId=".$type."&publishId=0";
		$url = "https://headline.taobao.com/feed/feedQuery.do?columnId=23&publishId=953215011400311690";
		$ch = curl_init();
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_URL => $url,
				CURLOPT_REFERER => $url,
				CURLOPT_AUTOREFERER => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_CONNECTTIMEOUT => 1,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36'
			)
		);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            print_r(curl_error($ch));
        }
        curl_close($ch);
        unset($_SESSION['count']);
		$result = iconv("GBK","UTF-8",$result);
		$result = trim($result);
		$this->number = $number;
		$this->type = $type;
		$this->content = str_replace('\\"','',$result);
		$this->display();
	}
	
	public function caijiinfo(){
		$url = $_GET['detailUrl'];
		$id = $_GET['publishId'];
		$number = $_GET['number'];
		$name = $_GET['names'];
		$pic = $_GET['piclist'];
		$urllist = split(",",$url);
		$name = split("&",$name);
		$pic = split("&",$pic);
		$_SESSION['countother'] = 0;
		$_SESSION['pic'] = array();
		for ($i = 0;$i < count($pic);$i++) {
			$_SESSION['pic'][$i] = $pic[$i];
		}
		$type = $_GET['type'];
		$_SESSION['type'] = $type;
		$page = $_GET['page'];
		$html = array();
		$_SESSION['createtime'] = date("Y-m-d H:i:s");
		$_SESSION['sql'] = "insert into wt_caiji(content,title,time,createtime,picimage,typeid) values";
		if ($page < $number) {
			for ($i = 0;$i < count($urllist);$i++) {
				array_push($html,"https://headline.taobao.com/feed/feedDetail.htm?id=".$urllist[$i]);
				$message['data'][$i]['name'] = $name[$i];
			}
			QueryList::run('Multi',[
				//锟斤拷锟缴硷拷锟斤拷锟接硷拷锟斤拷
				'list' => $html,
				'curl' => [
					'opt' => array(
					//锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟絚url锟斤拷锟斤拷
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_AUTOREFERER => true,
					//........
					),
					
					//锟斤拷锟斤拷锟竭筹拷锟斤拷
					'maxThread' => 20,
					//锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟�
					'maxTry' => 20
				],
				'success' => function($a){
					//锟缴硷拷锟斤拷锟斤拷
					$reg = array(
							"title"  =>array("div .detail-title","html"),
							"time"   =>array("div .detail-time","html"),
							"text"   =>array("div .detail","html")
					);
					$ql = QueryList::Query($a['content'],$reg,'','UTF-8','GB2312',true);
					$data = $ql->getData();
					$test = "";
						//锟斤拷询锟角凤拷锟斤拷锟截革拷
					$titleother = iconv("UTF-8","GBK",$data[0]['title']);
					$list = M("caiji")->where("title = '".$titleother."'")->select();
					$content = $data[0]['text'];
					
					$title = $data[0]['title'];
					$time = $data[0]['time'];
					$picimage = $_SESSION['pic'][$_SESSION['countother']];
					//file_put_contents("./log.txt",M()->getLastSql()."||",FILE_APPEND);
					if (!$list) {
						$_SESSION['sql'] .= "('".$content."','".$title."','".$time."','".$_SESSION['createtime']."','".$picimage."',".$_SESSION['type']."),";
						$_SESSION['count']++;
						$_SESSION['countother']++;
						
					}
					
				}
			]);
			$sql = substr($_SESSION['sql'],0,-1);
			$message['count'] = $_SESSION['count'];
			M("caiji")->query($sql);
			unset($_SESSION['type']);
			unset($_SESSION['pic']);
			unset($_SESSION['createtime']);
			unset($_SESSION['sql']);
			unset($_SESSION['countother']);
			//锟缴硷拷锟斤拷一页锟斤拷锟斤拷
			if ($page < $number) {
				$url = C("caijiurl")."?columnId=".$type."&publishId=".$id;
				$ch = curl_init();
				curl_setopt_array(
				$ch,
				array(
				CURLOPT_URL => $url,
				CURLOPT_REFERER => $url,
				CURLOPT_AUTOREFERER => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_CONNECTTIMEOUT => 1,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36'
						)
				);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
				$result = curl_exec($ch);
				if(curl_errno($ch)){
					print_r(curl_error($ch));
				}
				curl_close($ch);
				$content = "";
				$result = iconv("GBK","UTF-8",$result);
				$result = trim($result);
				$message['result'] = $result;
			}
		}
		echo json_encode($message);
	}
	
	public function articlemanager(){
		$and = "";
		if ($_GET['id']) {
			$and .= " and a.id = ".$_GET['id'];
		}
		if ($_GET['title']) {
			$and .= " and a.title like '%".$_GET['title']."%'";
		}
		if ($_GET['starttime']) {
			$and .= " and a.createtime >= '".$_GET['starttime']." 00:00:00'";
		}
		if ($_GET['endtime']) {
			$and .= " and a.createtime <= '".$_GET['endtime']." 23:59:59'";
		}
		if ($_GET['headertype']) {
			$and .= " and b.id = ".$_GET['headertype'];
		}
		import("ORG.Util.Page");
		$totalRows = M()->table("wt_caiji a")->join("left join wt_headertype b on a.typeid = b.columnid")->join("left join wt_read c on a.id = c.rid")->where("a.id is not null  ".$and)->count();
		$page = new Page($totalRows,30);
		$this->list = M()->table("wt_caiji a")->join("left join wt_headertype b on a.typeid = b.columnid")->join("left join wt_read c on a.id = c.rid")->where("a.id is not null  ".$and)->limit($page->firstRow,$page->listRows)->field("a.*,b.typename,sum(c.readnumber) as readnumber")->group("a.id")->order("a.id desc")->select();
// 		echo M()->getLastSql();
		$this->page = $page->show();
		$this->headertype = M("headertype")->select();
		$this->display();
	}
	
	public function delcaiji(){
		$id = $_GET['id'];
		$is = M("caiji")->where("id = ".$id)->delete();
		if ($is) {
			$data['msg'] = 1;
		} else {
			$data['msg'] = 0;
		}
		echo json_encode($data);
	}
	
	public function editconten(){
		if ($_GET['id']) {
			$this->classlist = M('caiji')->find($_GET['id']);
		}
		$this->headertype = M("headertype")->select();
		$this->display();
	}
	
	public function editcaiji(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->maxSize  = 3145728 ;
		$upload->allowExts  = array('jpg', 'png', 'jpeg');
		$upload->savePath =  './picimage/';
		$upload->upload();
		$info = $upload->getUploadFileInfo();
		if ($info[0]['savename']) {
			$data['picimage'] = './picimage/'.$info[0]['savename'];
		}
		$data['title'] = $_POST['title'];
		$data['typeid'] = $_POST['typeid'];
		$data['time'] = $_POST['time'];
		$data['content'] = $_POST['editor'];
		file_put_contents("./log.txt", $data['content']);
		$data['id'] = $_POST['id'];
		M("caiji")->save($data);
		$this->redirect("TaoBaoheader/articlemanager");
	}
	
	public function getgoodsinfo(){
		$id = $_POST['id'];
		$data = M("goods")->where("id = ".$id)->select();
		$list = M("url")->select();
		$data[0]['statusurl'] = "changurl('".$data[0]['Quan_link']."','".$id."','".$data[0]['GoodsID']."')";
		echo json_encode($data);
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
	
	public function saveimage(){
		import('ORG.Net.UploadFile');
		$date = date("Ymd");
		$upload = new UploadFile();
		$upload->maxSize  = 3145728 ;
		$upload->allowExts  = array('jpg', 'png', 'jpeg');
		$upload->savePath =  './picimage/'.$date.'/';
		$upload->upload();
		$info = $upload->getUploadFileInfo();
		if ($info[0]['savename']) {
			$data['picimage'] = './picimage/'.$date.'/'.$info[0]['savename'];
		}
		echo json_encode($data);
	}
	
	public function pusharticle(){
		$data['id'] = $_POST['id'];
		$data['shixiaotime'] = $_POST['shixiaotime'];
		$data['status'] = 1;
		$is = M("caiji")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 2;
		}
		echo json_encode($data2);
	}
	
	public function readinfo(){
		$and = "";
		if ($_GET['rid']){
			$and .= " and b.id = ".$_GET['rid'];
		}
		if ($_GET['title']){
			$and .= " and b.title like '%".$_GET['title']."%'";
		}
		if ($_GET['uid']){
			$and .= " and c.id = ".$_GET['uid'];
		}
		import("ORG.Util.Page");
		$totalRows = M()->table("wt_read a,wt_caiji b,wt_weixinuser c")->where("a.rid = b.id and a.openid = c.openid ".$and)->count();
		$page = new Page($totalRows,30);
		$list = M()->table("wt_read a,wt_caiji b,wt_weixinuser c")->where("a.rid = b.id and a.openid = c.openid ".$and)->limit($page->firstRow,$page->listRows)->field("a.*,b.title,c.nickname,c.id as uid")->order("a.readtime desc")->select();
		//算阅读数钱
		$pricelist = M("dictionary")->where("field = 'ReadPrice'")->select();
		for ($i = 0;$i < count($list);$i++) {
			$list[$i]['price'] = $list[$i]['readnumber']*$pricelist[0]['value'];
		}
		$this->list = $list;
// 				echo M()->getLastSql();
		$this->page = $page->show();
		$this->display();
	}
}
?>