<?php
class SystemManagerAction extends BaseAction{
	public function userlist(){
		$and = "";
		if ($_POST['usernameone']) {
			$and .= " a.username = '".$_POST['usernameone']."'";
		}
		import("ORG.Util.Page");
		$totalRows = M()->table("(select a.*,b.rolename,c.partname,c.id as pid from s_user a left join s_role b on a.roleid = b.id left join s_partment c on a.partid = c.id where a.id is not null ".$and.") a")->count();
		$page= new Page($totalRows,30);
		$this->part = M("partment")->select();
		$this->list = M()->table("s_user a")->join("left join s_role b on a.roleid = b.id")->join("left join s_partment c on a.partid = c.id")->where($and)->limit($page->firstRow,$page->listRows)->field("a.*,b.rolename,c.partname,c.id as partid")->select();
		$this->rolelist = M("role")->select();
		$this->page = $page->show();
		$this->display();
	}
	
	public function adduser () {
		if ($_POST['id']) {
			$data['id'] = $_POST['id'];
			$data['username'] = $_POST['username'];
			$data['realname'] = $_POST['realname'];
			$data['roleid'] = $_POST['roleid'];
			$data['password'] = $_POST['password'];
			$data['createtime'] = date("Y-m-d H:i:s");
			//鍒ゆ柇鏄惁鏄鏈�
			$myid = C("kefu_id");
			$str = explode(',',$myid);
			for ($i = 0; $i < count($str);$i++) {
			    if ($str[$i] == $data['roleid']) {
			        $data['state'] = 2;
			    }
			}
			//鍒ゆ柇鏄惁鏄繍钀�
			$myidother = C("yunying_id");
			$strother = explode(',',$myidother);
			for ($i = 0; $i < count($strother);$i++) {
			    if ($strother[$i] == $data['roleid']) {
			        $data['state'] = 3;
			    }
			}
			//鍒ゆ柇鏄惁鏄唬鐞�
			$myidotherone = C("daili_id");
			$strother = explode(',',$myidotherone);
			for ($i = 0; $i < count($strother);$i++) {
			    if ($strother[$i] == $data['roleid']) {
			        $data['state'] = 4;
			    }
			}
			if ($data['state'] == "" || $data['state'] == null) {
			    $data['state'] = 1;
			}
			$is = M("user")->save($data);
			if ($is) {
				$data2['msg'] = 3;
			} else {
				$data2['msg'] = 4;
			}
		} else {
			$data['username'] = $_POST['username'];
			$data['realname'] = $_POST['realname'];
			$data['roleid'] = $_POST['roleid'];
			$data['password'] = $_POST['password'];
			$data['createtime'] = date("Y-m-d H:i:s");
		    //鍒ゆ柇鏄惁鏄鏈�
			$myid = C("kefu_id");
			$str = explode(',',$myid);
			for ($i = 0; $i < count($str);$i++) {
			    if ($str[$i] == $data['roleid']) {
			        $data['state'] = 2;
			    }
			}
			//鍒ゆ柇鏄惁鏄繍钀�
			$myidother = C("yunying_id");
			$strother = explode(',',$myidother);
			for ($i = 0; $i < count($strother);$i++) {
			    if ($strother[$i] == $data['roleid']) {
			        $data['state'] = 3;
			    }
			}
			//鍒ゆ柇鏄惁鏄唬鐞�
			$myidotherone = C("daili_id");
			$strother = explode(',',$myidotherone);
			for ($i = 0; $i < count($strother);$i++) {
			    if ($strother[$i] == $data['roleid']) {
			        $data['state'] = 4;
			    }
			}
			if ($data['state'] == "" || $data['state'] == null) {
			    $data['state'] = 1;
			}
			$is = M("user")->add($data);
			if ($is) {
				$data2['msg'] = 1;
			} else {
				$data2['msg'] = 2;
			}
		}
		echo json_encode($data2);
	}
	
	public function deluser(){
		$id = $_GET['id'];
		$is = M("user")->where("id = ".$id)->delete();
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 2;
		}
		echo json_encode($data2);
	}
	public function menulist(){
		//鏌ヨ鎵�湁鐨勪竴绾ц彍鍗�
		$this->onelevel = M("menu")->where("parentid = 0")->order("id asc")->select();
		$this->display();
	}
	
	public function getonelevel(){
		$list = M("menu")->where("parentid = 0")->order("id asc")->select();
		if ($list) {
			for ($i = 0; $i < count($list);$i++) {
				if ($list[$i]['parentid'] == 0) {
					$list[$i]['level'] = '一级菜单';
				} else {
					$list[$i]['level'] = '二级菜单';
				}
			}
		}
		echo json_encode($list);
	}
	
	public function gettwolevel(){
	    if ($_GET['id'] > 0) {
    		$id = $_GET['id'];
    		$list = M("menu")->where("parentid = ".$id)->order("id asc")->select();
    		if ($list) {
    			for ($i = 0; $i < count($list);$i++) {
    				if ($list[$i]['parentid'] == 0) {
    					$list[$i]['level'] = '一级菜单';
    				} else if ($list[$i]['parentid'] != 0) {
    					$list[$i]['level'] = '二级菜单';
    				}
    			}
    		}
	    }
		echo json_encode($list);
	}
	
	public function addmenu(){
	    
		if ($_POST['id']) {
		    $flag = false;
		    $list = M("menu")->where("ename = '".$_POST['ename']."' and id = ".$_POST['id'])->select();
		    if ($list) {
		        $flag = true;
		    } else {
		        $listone = M("menu")->where("ename = '".$_POST['ename']."' and id != ".$_POST['id'])->select();
		        if ($listone) {
		            $flag = false;
		        } else {
		            $flag = true;
		        }
		    } 
		    if ($flag) {
    			//鍒ゆ柇鏄嚑绾ц彍鍗�
    			$data['id'] = $_POST['id'];
    			$data['name'] = $_POST['name'];
    			if ($_POST['levelid'] == 1) {
    				$data['parentid'] = 0;
    				$data['ename'] = $_POST['ename'];
    			} else if ($_POST['levelid'] == 2) {
    				$data['parentid'] = $_POST['parentid'];
    				//鏌ヨ涓�骇鑿滃崟ename
    				$list = M("menu")->where("id = ".$_POST['parentid'])->select();
    				$ename = $list[0]['ename'];
    				$data['url'] = "index.php?s=".$ename."/".$_POST['ename'];
    				$data['ename'] = $_POST['ename'];
    			}
    			$is = M("menu")->save($data);
		    } 
		    else {
		        $data2['msg'] = 2;
		    }
		    
		} else {
		    $list = M("menu")->where("ename = '".$_POST['ename']."'")->select();
		    if (!$list) {
    			//鍒ゆ柇鏄嚑绾ц彍鍗�
    			$data['name'] = $_POST['name'];
    			if ($_POST['levelid'] == 1) {
    				$data['parentid'] = 0;
    				$data['ename'] = $_POST['ename'];
    			} else if ($_POST['levelid'] == 2) {
    				$data['parentid'] = $_POST['parentid'];
    				//鏌ヨ涓�骇鑿滃崟ename
    				$list = M("menu")->where("id = ".$_POST['parentid'])->select();
    				$ename = $list[0]['ename'];
    				$data['url'] = "index.php?s=".$ename."/".$_POST['ename'];
    				$data['ename'] = $_POST['ename'];
    			}
    			$is = M("menu")->add($data);
		    } else {
		        $data2['msg'] = 2;
		    }
		}
		if ($is) {
			$data2['msg'] = 1;
		} else if (!$is && ($data2['msg'] == "" || $data2['msg'] == null)) {
			$data2['msg'] = 0; 
		}
		echo json_encode($data2);
	}
	
	public function delmenu(){
		$id = $_GET['id'];
		//鏌ヨ鏄惁鏄竴绾ц彍鍗�
		$list = M("menu")->where("id = ".$id)->select();
		if ($list) {
			//涓�骇鑿滃崟
			if ($list[0]['parentid'] == 0) {
				//鏌ヨ鏄惁鏈夊瓙鑿滃崟锛屽鏋滄湁鍒欒鍏堝垹闄ゅ瓙鑿滃崟鎵嶈兘鍒犻櫎
				$otherlist = M("menu")->where("parentid = ".$id)->select();
				if ($otherlist) {
					$data['msg'] = 2;
				} else {
					M("menu")->where("id = ".$id)->delete();
					$data['msg'] = 1;
				}
			//浜岀骇鑿滃崟
			} else {
				M("menu")->where("id = ".$id)->delete();
				$data['msg'] = 1;
			}
		} else {
			$data['msg'] = 3;
		}
		echo json_encode($data);
	}
	
	public function rolelist(){
		$this->list = M()->table("s_role a")->join("left join s_access b on a.id = b.roleid")->field("a.*,b.mid")->select();
		$list = M("menu")->where("parentid = 0")->select();
		$otherlist = M("menu")->where("parentid != 0")->select();
		for ($i = 0; $i < count($list);$i++) {
			for($y = 0;$y < count($otherlist);$y++){
				if ($list[$i]['id'] == $otherlist[$y]['parentid']) {
					$list[$i]['sonmenu'][$count] = $otherlist[$y];
					$count++;
				}
			}
		}
		for ($i = 0; $i < count($list);$i++) {
			if (!$list[$i]['sonmenu']){
				unset($list[$i]);
			}
		}
		$this->menulist = $list;
		$this->display();
	}
	
	public function addrole(){
		if ($_POST['id']) {
			$data['rolename'] = $_POST['rolename'];
			$data['id'] = $_POST['id'];
			$is = M("role")->save($data);
			if ($is) {
				$data2['msg'] = 3;
			} else {
				$data2['msg'] = 4;
			}
		} else {
			//鏂板瑙掕壊
			$data['rolename'] = $_POST['rolename'];
			$data['createtime'] = date('Y-m-d H:i:s');
			$is = M("role")->add($data);
			if ($is) {
				$data2['msg'] = 1;
			} else {
				$data2['msg'] = 2;
			}
		}
		echo json_encode($data2);
	}
	
	public function delrole(){
		$id = $_POST['id'];
		//鏌ヨ鏄惁鏈夊湪浣跨敤鐢ㄦ埛锛屾湁鍒欎笉鑳藉垹闄�
		$list = M("user")->where("roleid = ".$id)->select();
		if ($list) {
			$data3['msg'] = 3;
		} else {
			//鍒犻櫎access琛ㄤ俊鎭�
			$flag = true;
			$accesslist = M("access")->where("roleid = ".$id)->select();
			if ($accesslist) {
				$flag = false;
				$flag = M("access")->where("roleid = ".$id)->delete();
			}
			$is = M("role")->where("id = ".$id)->delete();
			if ($is && $flag) {
				$data3['msg'] = 1;
			} else {
				$data3['msg'] = 2;
			}
		}
		echo json_encode($data3);
	}
	
	public function addaccess(){
		$data['roleid'] = $_POST['id'];
		//娣诲姞鏃跺厛鏌ヨ鏄惁浠ュ墠鏈夋潈闄愶紝鏈夊垯鍏堝垹闄ゆ帀浠ュ墠鐨勫啀娣诲姞
		$list = M("access")->where("roleid = ".$_POST['id'])->select();
		if ($list) {
			M("access")->where("roleid = ".$_POST['id'])->delete();
		}
		$data['mid'] = $_POST['access'];
		$data['createtime'] = date('Y-m-d H:i:s');
		$is = M("access")->add($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 2;
		}
		echo json_encode($data2);
	}
	
	public function partmentmanager(){
	    $and = "";
	    if ($_POST['part']) {
	        $and .=" typename = '".$_POST['part']."'";
	    }
	    $this->list = M("goodstype")->where($and)->order("id desc")->select();
	    $this->display();
	}
	
	public function editpartment(){
	    if ($_POST['id']) {
	        $data['id'] = $_POST['id'];
	        $data['typename'] = $_POST['typename'];
	        $data['tkid'] = $_POST['tkid'];
	        $data['createtime'] = date("Y-m-d");
	        $is = M("goodstype")->save($data);
	        if ($is) {
	            $data2['msg'] = 1;
	        } else {
	            $data2['msg'] = 2;
	        }
	    } else {
	        $data['typename'] = $_POST['typename'];
	        $data['tkid'] = $_POST['tkid'];
	        $data['createtime'] = date("Y-m-d");
	        $is = M("goodstype")->add($data);
	        if ($is) {
	            $data2['msg'] = 1;
	        } else {
	            $data2['msg'] = 2;
	        }
	    }
	    echo json_encode($data2);
	}
	
	public function dictionaryset(){
		$and = "";
		if ($_GET['fvalue']) {
			$and .= "(field like '%".$_GET['fvalue']."%' or value like '%".$_GET['fvalue']."%')";
		}
		$this->list = M("dictionary")->where($and)->select();
		echo M()->getLastSql();
		$this->display();
	}
	
	public function adddictionary(){
		if ($_POST['id']) {
			$data['id'] = $_POST['id'];
			$data['field'] = $_POST['field'];
			$data['value'] = $_POST['value'];
			$data['remark'] = $_POST['remark'];
			$data['createtime'] = date("Y-m-d H:i:s");
			$is = M("dictionary")->save($data);
		} else {
			$data['field'] = $_POST['field'];
			$data['value'] = $_POST['value'];
			$data['remark'] = $_POST['remark'];
			$data['createtime'] = date("Y-m-d H:i:s");
			$is = M("dictionary")->add($data);
		}
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
	
	public function deldictionary(){
		$id = $_GET['id'];
		$is = M("dictionary")->where("id = ".$id)->delete();
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 2;
		}
		echo json_encode($data2);
	}
}
?>