<?php

class LinkAction extends Action {
    public function index(){
    	$map = array();
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		
		$map_sql = 'pkid>0 ';
		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		}
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 3;
		
		$link = new Model("Link"); 
		if($map){
			$list = $link->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $link->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		
		
		$this->assign('link_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $link->where($map_sql)->count();
		}else{
			$count  = $link->count();
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
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$link = new Model("Link");
    	$arr_form = $link->query('SELECT * FROM link WHERE pkid='.$pkid);
    	
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
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Link/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}*/
    	
		$link = new Model("Link");
		$link->create();
		//$link->thumb = $up_path.$info[0]['savename'];
		$link->add();
		
		//$msg = mb_convert_encoding("添加成功","UTF-8","GB2312");
    	//redirect("/Backend/Link/index",2,$msg);
    	redirect("/Backend/Link/index");
    }
    
    
    public function renew(){
    	
		$link = new Model("Link");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['url'] = $this->_param('url');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		
		/*if(!empty($_FILE['thumb'])){
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Link/';// 设置附件上传目录;
			$upload->savePath =  $up_path;
			
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
			}
			
			if(!empty($info[0]['savename'])){
				$data['thumb'] = $up_path.$info[0]['savename'];
			}
		}*/
		
		$link->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Link/index",2,$msg);
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$link = new Model("Link");
		
		$link->query('DELETE FROM link WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Link/index",1,$msg);
    	//redirect("/Backend/Link/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$link = new Model("Link");
		
		$link->query("UPDATE link SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Link/index",1,$msg);
    	//redirect("/Backend/Link/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$link = new Model("Link");
		
		$link->query("UPDATE link SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Link/index",1,$msg);
    	//redirect("/Backend/Link/index");
    }
    
    
    
}
?>