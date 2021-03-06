<?php
require 'aop/AopClient.php';
require 'aop/SignData.php';
require 'aop/request/AlipayFundTransToaccountTransferRequest.php';
require 'aop/request/AlipayFundTransOrderQueryRequest.php';
include 'aliyun/aliyun-php-sdk-core/Config.php';
include 'aliyun/aliyun-php-sdk-push/Push/Request/V20160801/PushMessageToAndroidRequest.php';
use Push\Request\V20160801 as Push;

class AlipayCenterAction extends BaseAction{
	public function userpay(){
		import("ORG.Util.Page");
		$and = "";
		if ($_GET['alipay']) {
			$and .= " and a.alipay like '%".$_GET['alipay']."%'";
		}
		if ($_GET['realname']) {
			$and .= " and a.realname = '".$_GET['realname']."'";
		}
		if ($_GET['starttime']) {
			$and .= " and a.time >= '".$_GET['starttime']." 00:00:00'";
		}
		if ($_GET['endtime']) {
			$and .= " and a.time <= '".$_GET['endtime']." 23:59:59'";
		}
		$totalRows = M()->table("wt_tixian a,wt_weixinuser b")->where("a.openid = b.openid and a.status = 0 ".$and)->count();
		$page = new Page($totalRows,30);
		$this->list = M()->table("wt_tixian a,wt_weixinuser b")->where("a.openid = b.openid and a.status = 0 ".$and)->limit($page->firstRow,$page->listRows)->field("a.*,b.nickname,b.imageurl")->order("a.time asc")->select();
		$this->page = $page->show();
		$this->display();
	}
	
	public function paymoney(){
		$id = $_GET['id'];
		$alipay = $_GET['alipay'];
		$realname = $_GET['realname'];
		$tixian = $_GET['tixian'];
		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = '2017082308341452';
		$aop->rsaPrivateKey = 'MIIEpQIBAAKCAQEA6xD79GRPgrzW6+h/1v/6qoH3QXEztTkU9b9Bz0hM3ATH3Mt19faopt5thDhvWOGynIJGlOwBBgcm1vcBve9pC9lLKIRZJpUiEFErg9mswqVGifAuo1e4DEqHXMyzLZpEgBPGow6hKe+QPV4tHkRd9ZZMkaFslNskiHLueAB+vxiurpuLRJ+QPi3yVzhVmj/uQ9KOO/qylOdhvHLM9+wpSndsz7qlJZn2S1urkYIrDaasTbtDOAHymSiyc/FmRQHhSIuVll6Zrs+oZX9EqywvjIlgI+chh8WVn21vvS9JdKzjfv3dEZ2FV4grnKsRRj8S+CEHP07FXtTRGSR9NwW0/QIDAQABAoIBAAHcjZI+5R5M7orJuZFzT4HgK5waEGXEAPV9QUEp+mnXo9mMxu9et7Eym71rUP5sMyToJzTwtYVdsyIKrLnbVoKggubcPXR/w9hLPEP8ClE0S921yDjE7hEy2eWTPWwEB7zX6z/JUt9pJURHJaZStkfGm+6fT6PSJHTsLBVJM7fBr2WQS621xUcpPDuPro5kd1xIQqCmomDhCx64HUySHqujTuAScgeylic/3rk2Xel+aJPzYUo3hgisBD5PxyHu7TrG42uAmpB7zx/UHZMz73hNmKhrP3p6WOFKiwq4Ql3KM117UYMFnZtgG7cN5q+On5Z1w+KdDoN6MQqPzJ6LfYECgYEA+rfPMUfxFn4nSl0GCnKrVTRt0AJs2/tNdJ6kaDnEWdDKBd2YJ2BXY32biY6yE3JUTqRAfb6+psolZAi5zuOGdRfOYsW76BW8fIt9qDJFTqKXyP0iGoyGx86hdFgB0awKacnYL914G5nwYFEutakRwgUivqm+ief0VRbF6utoSR0CgYEA8ATC3DQyrJbVIuYD3poBPHO2LMIOkE3syzLmjdacl9taSC0q04bOPQ7ZTWB8q6cAczAoxawpB6SRZ+gAIKYD+WhrOIqU2YIzbSPgXS2s1fHNmhp8E3KzCS9LE+2pO5UObA2Ym8WYCBSL6U2l62s0pML5LkE+yEWyWxUxGkOrNWECgYEA4DH1yO+4GpD8uXatrDIWG78iY1uH1L7H6HzkL39Yoagev1gEc1GnU4RCzaMYgmM+GISwVsF24tbo0Kcs4GB9H8AGAjUnbsXhIs3ps7ogV8z1Rfl1wZ/f9lRD9DGujrv1I9wmwcadXLEvTjHKPw/MnV7STwSjKdaNmnkeyo9vVdECgYEAxW/FMwqlHJ++l8SKLlSU67trggnnnQDc5pHqYYbGrYw9Cd5Nuk7L3CIY0rOqGjF4B0j4YRKrdaQEEGshn7mwzzr76DjnFL0rvH2klEF8SUltDXT316+kYsvKgIwhh+enQ28zt/+kfhEYvkOnVq6LUXUpMMlu8aOTgaU/DRHZBkECgYEAorf3WTbHLnjx9PbcH4ZcpRKWlr9q+Au4/sQ5ehUbLSGn14Ceo1ifxwNoEgmoWt93UdMiLHgjPE3syEpQVA/KEy94FVd45k/hRrKgOK/HQIF+a7pTpZu1FVYcFpJEZBD1+JfbkGT09i6UtlJ4erTuvwx56rpJUs3ZuBOk2Yskf1Y=';
		$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnp72n9L/yJrc1dba2XL0VxTNhZjf6M4s+8VRiQsU4DMGNFVKu7hBhrOCpBMzFtgUYZlOGch//YKZjaWj1SXlOWiu8/6g4cba+CuBHkn1k18LyDC2JtTZAnYTm1iLdFnmLoV4XY4YXNRf+RqvehNYzd5XoqCGuhHSd7vkPPa8cmwAa5tSl4ww/feJa+rV6zvYsuyK9g5PknogtwYQHKqdGVz7OLrcdxgS3Lf4fQrpr52rYw6LRCYOeqpMy+kTXm3rABZ7ovJmi5R86iQED1mGmQAq6xLZfh8jAR0N5h+jcVYtR68QG7aHLeFuvRvCcZreR0A5O1YL3dsfRWHIjYfcqwIDAQAB';
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset = 'utf-8';
		$aop->format = 'json';
		$yi = rand(0,9);
		$er = rand(0,9);
		$san = rand(0,9);
		$si = rand(0,9);
		$wu = rand(0,9);
		$liu = rand(0,9);
		$qi = rand(0,9);
		$ba = rand(0,9);
		$jiu = rand(0,9);
		$bizno = $yi.$er.$san.$si.$wu.$liu.$qi.$ba.$jiu;
		$request = new AlipayFundTransToaccountTransferRequest();
		$request->setBizContent ( 
				"{" . " \"out_biz_no\":\"".$bizno."\"," . 
				" \"payee_type\":\"ALIPAY_LOGONID\"," . 
				" \"payee_account\":\"".trim($alipay)."\"," . 
				" \"payee_real_name\":\"".trim($realname)."\"," .
				" \"amount\":\"".$tixian."\"," . 
				" \"remark\":\"合作人收益\"" . " }" );
		$result = $aop->execute ( $request );
		$responseNode = str_replace ( ".", "_", $request->getApiMethodName () ) . "_response";
		$resultCode = $result->$responseNode->code;
		file_put_contents("./log.txt", $bizno);
		if(!empty($resultCode)&&$resultCode == 10000){
			$data['orderid'] = $result->$responseNode->order_id;
			$data['bizno'] = $bizno;
			$data['paymoney'] = $tixian;
			$data['realname'] = $realname;
			$data['username'] = $alipay;
			$data['paydate'] = $result->$responseNode->pay_date;
			$data['createtime'] = date("Y-m-d H:i:s");
			$data['sign'] = $result->sign;
			M("payrecord")->add($data);
			$data3['id'] = $id;
			$data3['status'] = 1;
			M("tixian")->save($data3);
			$data2['msg'] = 1;
			//插入一条打款成功消息
			$data4['openid'] = $_GET['openid'];
			$data4['content'] = '您申请的提现的'.$tixian.'元已经打款.';
			$data4['createtime'] = date("Y-m-d H:i:s");
			M("paymoneymessage")->add($data4);
			//推送一条消息
			$result = $this->pushpaymoneymessage($id,3,$data4['content'],$_GET['openid']);
			echo json_encode($data2);
		} else {
			$data2['msg'] = $result->$responseNode->sub_msg;
			echo json_encode($data2);
		}
	}
	
	public function moneyback() {
		//改变打款记录状态为退款
		$data['id'] = $_POST['id'];
		$data['status'] = 2;
		$is = M("tixian")->save($data);
		//改变余额加上提现金额
		$list = M("yue")->where("openid = '".$_POST['openid']."'")->select();
		$data2['yue'] = $list[0]['yue'] + $_POST['tixian'];
		$data2['id'] = $list[0]['id'];
		$flag = M("yue")->save($data2);
		if ($is && $flag) {
			$data3['msg'] = 1;
		} else {
			$data3['msg'] = 0;
		} 
		echo json_encode($data3);
	}
	
	public function payrecord(){
		import("ORG.Util.Page");
		$and = "";
		if ($_GET['alipay']) {
			$and .= " and username like '%".$_GET['alipay']."%'";
		}
		if ($_GET['realname']) {
			$and .= " and realname = '".$_GET['realname']."'";
		}
		if ($_GET['starttime']) {
			$and .= " and paydate >= '".$_GET['starttime']." 00:00:00'";
		}
		if ($_GET['endtime']) {
			$and .= " and paydate <= '".$_GET['endtime']." 23:59:59'";
		}
		$totalRows = M("payrecord")->where("id is not null ".$and)->count();
		$page = new Page($totalRows,30);
		$this->list = M("payrecord")->where("id is not null ".$and)->limit($page->firstRow,$page->listRows)->order("paydate desc")->select();
		$this->page = $page->show();
		$this->display();
	}
	
	public function searchpaymoney(){
		$orderid = trim($_POST['orderid']);
		$bizno = trim($_POST['bizno']);
		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = '2017082308341452';
		$aop->rsaPrivateKey = 'MIIEpQIBAAKCAQEA6xD79GRPgrzW6+h/1v/6qoH3QXEztTkU9b9Bz0hM3ATH3Mt19faopt5thDhvWOGynIJGlOwBBgcm1vcBve9pC9lLKIRZJpUiEFErg9mswqVGifAuo1e4DEqHXMyzLZpEgBPGow6hKe+QPV4tHkRd9ZZMkaFslNskiHLueAB+vxiurpuLRJ+QPi3yVzhVmj/uQ9KOO/qylOdhvHLM9+wpSndsz7qlJZn2S1urkYIrDaasTbtDOAHymSiyc/FmRQHhSIuVll6Zrs+oZX9EqywvjIlgI+chh8WVn21vvS9JdKzjfv3dEZ2FV4grnKsRRj8S+CEHP07FXtTRGSR9NwW0/QIDAQABAoIBAAHcjZI+5R5M7orJuZFzT4HgK5waEGXEAPV9QUEp+mnXo9mMxu9et7Eym71rUP5sMyToJzTwtYVdsyIKrLnbVoKggubcPXR/w9hLPEP8ClE0S921yDjE7hEy2eWTPWwEB7zX6z/JUt9pJURHJaZStkfGm+6fT6PSJHTsLBVJM7fBr2WQS621xUcpPDuPro5kd1xIQqCmomDhCx64HUySHqujTuAScgeylic/3rk2Xel+aJPzYUo3hgisBD5PxyHu7TrG42uAmpB7zx/UHZMz73hNmKhrP3p6WOFKiwq4Ql3KM117UYMFnZtgG7cN5q+On5Z1w+KdDoN6MQqPzJ6LfYECgYEA+rfPMUfxFn4nSl0GCnKrVTRt0AJs2/tNdJ6kaDnEWdDKBd2YJ2BXY32biY6yE3JUTqRAfb6+psolZAi5zuOGdRfOYsW76BW8fIt9qDJFTqKXyP0iGoyGx86hdFgB0awKacnYL914G5nwYFEutakRwgUivqm+ief0VRbF6utoSR0CgYEA8ATC3DQyrJbVIuYD3poBPHO2LMIOkE3syzLmjdacl9taSC0q04bOPQ7ZTWB8q6cAczAoxawpB6SRZ+gAIKYD+WhrOIqU2YIzbSPgXS2s1fHNmhp8E3KzCS9LE+2pO5UObA2Ym8WYCBSL6U2l62s0pML5LkE+yEWyWxUxGkOrNWECgYEA4DH1yO+4GpD8uXatrDIWG78iY1uH1L7H6HzkL39Yoagev1gEc1GnU4RCzaMYgmM+GISwVsF24tbo0Kcs4GB9H8AGAjUnbsXhIs3ps7ogV8z1Rfl1wZ/f9lRD9DGujrv1I9wmwcadXLEvTjHKPw/MnV7STwSjKdaNmnkeyo9vVdECgYEAxW/FMwqlHJ++l8SKLlSU67trggnnnQDc5pHqYYbGrYw9Cd5Nuk7L3CIY0rOqGjF4B0j4YRKrdaQEEGshn7mwzzr76DjnFL0rvH2klEF8SUltDXT316+kYsvKgIwhh+enQ28zt/+kfhEYvkOnVq6LUXUpMMlu8aOTgaU/DRHZBkECgYEAorf3WTbHLnjx9PbcH4ZcpRKWlr9q+Au4/sQ5ehUbLSGn14Ceo1ifxwNoEgmoWt93UdMiLHgjPE3syEpQVA/KEy94FVd45k/hRrKgOK/HQIF+a7pTpZu1FVYcFpJEZBD1+JfbkGT09i6UtlJ4erTuvwx56rpJUs3ZuBOk2Yskf1Y=';
		$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnp72n9L/yJrc1dba2XL0VxTNhZjf6M4s+8VRiQsU4DMGNFVKu7hBhrOCpBMzFtgUYZlOGch//YKZjaWj1SXlOWiu8/6g4cba+CuBHkn1k18LyDC2JtTZAnYTm1iLdFnmLoV4XY4YXNRf+RqvehNYzd5XoqCGuhHSd7vkPPa8cmwAa5tSl4ww/feJa+rV6zvYsuyK9g5PknogtwYQHKqdGVz7OLrcdxgS3Lf4fQrpr52rYw6LRCYOeqpMy+kTXm3rABZ7ovJmi5R86iQED1mGmQAq6xLZfh8jAR0N5h+jcVYtR68QG7aHLeFuvRvCcZreR0A5O1YL3dsfRWHIjYfcqwIDAQAB';
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset = 'utf-8';
		$aop->format = 'json';
		$request = new AlipayFundTransOrderQueryRequest();
		$request->setBizContent("{" .
				"\"out_biz_no\":\"".$bizno."\"," .
				"\"order_id\":\"".$orderid."\"" .
				"  }");
		$result = $aop->execute ( $request );
		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->$responseNode->code;
		if(!empty($resultCode)&& $resultCode == 10000){
			$data3['msg'] = 1;
		} else {
// 			if ($result->$responseNode->status == 'FAIL') {
// 				$errormessage = $result->$responseNode->fail_reason."||".$result->$responseNode->error_code;
// 				file_put_contents('./alipayerror/error.txt',$errormessage);
// 				$data2['status'] = 0;
// 				M("alipay")->where("username = '".$list[0]['username']."' and paytime = '".$list[0]['paydate']."'")->save($data2);
// 			}
			$data3['msg'] = 2;
		}
		echo json_encode($data3);
	}
	
	//推送消息
	public function pushpaymoneymessage($id,$type,$content,$openid){
		$accessKeyId = C('accessKeyId');
		$accessKeySecret = C('accessKeySecret');
		$appKey = C('appKey');
		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $accessKeyId, $accessKeySecret);
		$client = new DefaultAcsClient($iClientProfile);
		$request = new Push\PushNoticeToiOSRequest();
		$request->setAppKey($appKey);
		$request->setTarget("ACCOUNT"); //推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALL: 推送给全部
		$request->setTargetValue($openid); //根据Target来设定，如Target=DEVICE, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
		$request->setApnsEnv("DEV");//iOS的通知是通过APNS中心来发送的，需要填写对应的环境信息. DEV :表示开发环境, PRODUCT: 表示生产环境
		$request->setBody($content);
		$request->setExtParameters("{\"id\":\"".$id."\",\"type\":\"".$type."\",\"url\":\"".$url."\"}");
		$response = $client->getAcsResponse($request);
		if ($response) {
			$result = 1;
		} else {
			$result = 2;
		}
		return $result;
	}
}
?>