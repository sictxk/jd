<?php

class CardAction extends Action {
    public function index(){
    	$map = array();
		$map['CardNo'] = !empty($_POST['CardNo']) ? $_POST['CardNo'] : (!empty($_GET['CardNo']) ? $_GET['CardNo'] : '');
		$map['Status'] = !empty($_POST['Status']) ? $_POST['Status'] : (!empty($_GET['Status']) ? $_GET['Status'] : '');
		$map['AssignStatus'] = !empty($_POST['AssignStatus']) ? $_POST['AssignStatus'] : (!empty($_GET['AssignStatus']) ? $_GET['AssignStatus'] : '');
		
		$map_sql = "SELECT *,case when AgentId>0 then (select username from agent where id=card.AgentId) else 0 end as AgentName,".
					" case when StudentId>0 then (select account from user where pkid=card.StudentId) else 0 end as studentName FROM card  WHERE 1=1 ";
		$map_sql_count = "SELECT count(*) AS total FROM card WHERE 1=1 ";
		
		if(!empty($map['CardNo'])){
			$map_sql .= " AND CardNo = ".$map['CardNo'];
			$map_sql_count .= " AND CardNo =".$map['CardNo'];
		}
		if(!empty($map['Status'])){
		 	$map_sql .= " AND Status=".$map['Status'];
		 	$map_sql_count .= " AND Status=".$map['Status'];
		}
		if(!empty($map['AssignStatus'])){
		 	$map_sql .= " AND IsAssign=".$map['AssignStatus'];
		 	$map_sql_count .= " AND IsAssign=".$map['AssignStatus'];
		}
		$this->assign('cardNo',$map['CardNo']);
		$this->assign('value',$map['Status']);
		$this->assign('assign_value',$map['AssignStatus']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= "  limit ".($cur_page-1)*$page_size.",".$page_size;
		
		$Card = new Model("Card"); 
		$list = $Card->query($map_sql);
		$this->assign('card_list',$list);
		
		import("ORG.Util.Page");
		$data_count  = $Card->query($map_sql_count);
		$Page = new Page($data_count[0]['total'],$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
    	
    		$this->assign('Status', array('0'=>'未使用','1'=>'已使用','3'=>'已锁定'));
    		$this->assign('IsAssign', array('0'=>'未分配','1'=>'已分配'));
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$Card = new Model("Card");
    	$arr_form = $Card->query('SELECT * FROM card WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('value', $arr_form[0]['status']);
    	
		$this->display();
    }
    
    public function save(){
    	
		/*import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Card/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}*/
    	
		$Card = new Model("Card");
		$Card->create();
		//$Card->thumb = $up_path.$info[0]['savename'];
		$Card->add();
		

    	redirect("/Agent/Card/index");
    }
    
    
    public function renew(){
    	
		$Card = new Model("Card");
		$data['pkid'] = $this->_param('pkid');
		$data['CardNo'] = $this->_param('CardNo');
		$data['url'] = $this->_param('url');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		

		$Card->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Agent/Card/index",2,$msg);
    }
    
    

    
    public function lock(){
    	
    		$cardNo = $this->_param('CardNo'); 
    	
		$Card = new Model("Card");
		
		$Card->query("UPDATE Card SET status=3 WHERE CardNo=".$cardNo);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    		redirect("/Agent/Card/index",1,$msg);
    }
    
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Card = new Model("Card");
		
		$Card->query("UPDATE Card SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Agent/Card/index",1,$msg);
    	//redirect("/Agent/Card/index");
    }
    
    
    public function doAssign(){
    	
    	$agent = new Model("Agent");
    	
    	$parentId = $_SESSION['agent_account']['Id'];
    	$agentResult = $agent->where("Status=1 AND parentId=".$parentId)->select();
    	$agentList = array();
    	foreach($agentResult as $key=>$val){
    		$agentList[$val['Id']] = $val['UserName'];
    	}
    	$this->assign('agentList',$agentList);
    	$this->display('assign');
    }
    
    public function confirmAssign(){
    	
    	$agent = new Model("Agent");
    	$agentId = I('post.agentId');
    	$quantity = I('post.quantity');
    	
    	$card = new Model("Card");
    	$cardTotal = $card->where("Status=0")->count();
    	if($cardTotal>$quantity){
    		$cardList = $card->field('CardNo')->where("Status=0")->order('CardNo ASC')->limit(0,$quantity)->select();
    		$startCard = $cardList[0]['CardNo'];
    		$finishCard = $cardList[$quantity-1]['CardNo'];
    		//分配卡号
    		$card->query("UPDATE Card SET agentId=".$agentId." WHERE Status=0 ORDER BY CardNo ASC limit 0,".$quantity);
    		//写日志
    		$card_assign = new Model("CardAssign");
    		$data['AgentId'] = $agentId;
    		$data['StartNo'] = $startCard;
    		$data['EndNo'] = $finishCard;
    		$data['AllocCount'] = $quantity;
    		$data['Creater'] = $_SESSION['agent_account']['Id'];
    		$card_assign->data($data)->add();
    	}else{
    		print "<script language='javascript'>alert('剩余卡号不足');location.href='/Agent/Agent/doAssign';</script>";exit;
    	}
    }
    
}
?>