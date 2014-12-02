<?php

class LeifengAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';
		
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['deal_status'] = !empty($_POST['deal_status']) ? $_POST['deal_status'] : (!empty($_GET['deal_status']) ? $_GET['deal_status'] : '');
		
		if(!empty($map['title'])){
			$map_sql .= "AND agency_title like '%".$map['title']."%'";
		}
		if(!empty($map['deal_status'])){
		 	$map_sql .= " AND deal_status='".$map['deal_status']."'";
		 }
    	$this->assign('map',$map);
    	$this->assign('value',$map['deal_status']);
		
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 6;
		$Leifeng = new Model("Leifeng");

		if($map){
			$list = $Leifeng->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $Leifeng->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		foreach($list as $k=>$v){
			$list[$k]['title'] = nl2br($v['agency_title']);
		}

		$this->assign('Leifeng_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $Leifeng->where($map_sql)->count();
		}else{
			$count  = $Leifeng->count();
		}
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
    	
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('deal_status', array('0'=>'未处理','1'=>'已确认','2'=>'已解决'));
		

    			
		$this->display();
    }

    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$Leifeng = new Model("Leifeng");
    	$arr_form = $Leifeng->query('SELECT * FROM Leifeng WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('Leifeng_type', array(1=>'前台',2=>'商家'));
    	$this->assign('value', $arr_form[0]['status']);
        $this->assign('type_value', $arr_form[0]['type']);

		
		$this->display();
    }

    
    
    public function renew(){
    	
		$Leifeng = new Model("Leifeng");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['author'] = $this->_param('author');
		$data['source'] = $this->_param('source');
		$data['intro'] = $this->_param('intro');
		
		$data['context'] = $this->_param('context');
		$status = $this->_param('status');
		$data['status'] = $status[0];

        $type = $this->_param('type');
        $data['type'] = $type[0];
		
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Leifeng/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		//print_r($_FILES);
		if($_FILES['picture']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$data['picture'] = '/Public/Upload/Leifeng/'.$info[0]['savename'];
			}
		}
		
		//print_r($data);die;
		$Leifeng->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Leifeng/index",2,$msg);
		
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Leifeng = new Model("Leifeng");
		
		$sql = 'DELETE FROM Leifeng WHERE pkid='.$pkid;
		//echo $sql;die;
		$Leifeng->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Leifeng/index",1,$msg);
    }
    
    public function confirm(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Leifeng = new Model("Leifeng");
		
		$d_time = date("Y-m-d H:i:s");
		$sql = "UPDATE Leifeng SET deal_status=1 ,confirm_time='".$d_time."' WHERE pkid=".$pkid;
		//echo $sql;die;
		$Leifeng->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Leifeng/index",1,$msg);
    }
    
    public function deal(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Leifeng = new Model("Leifeng");
		
		$d_time = date("Y-m-d H:i:s");
		$sql = "UPDATE Leifeng SET deal_status=2 ,deal_time='".$d_time."' WHERE pkid=".$pkid;
		//echo $sql;die;
		$Leifeng->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Leifeng/index",1,$msg);
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Leifeng = new Model("Leifeng");

		$Leifeng->query("UPDATE Leifeng SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Leifeng/index",1,$msg);
    	//redirect("/Backend/Leifeng/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Leifeng = new Model("Leifeng");

		$Leifeng->query("UPDATE Leifeng SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Leifeng/index",1,$msg);
    	//redirect("/Backend/Leifeng/index");
    }
    

}
?>