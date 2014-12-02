<?php

class PaperAction extends Action {
    public function index(){
    	$map = array();
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		$map['grade_id'] = !empty($_POST['grade_id']) ? $_POST['grade_id'] : (!empty($_GET['grade_id']) ? $_GET['grade_id'] : '');
		$map['course_id'] = !empty($_POST['course_id']) ? $_POST['course_id'] : (!empty($_GET['course_id']) ? $_GET['course_id'] : '');
		$map_sql = 'pkid>0 ';
		$map_sql_list = "SELECT p.pkid,p.grade_id,p.title,p.path,p.hits_num,p.status,p.ctime,c.title as course_title FROM paper p LEFT JOIN course c ON p.course_id=c.course_id WHERE p.pkid>0 ";
		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
			$map_sql_list .= "AND p.title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 	$map_sql_list .= " AND p.status='".$map['status']."'";
		}
		if(!empty($map['grade_id'])){
		 	$map_sql .= " AND grade_id='".$map['grade_id']."'";
		 	$map_sql_list .= " AND p.grade_id='".$map['grade_id']."'";
		}
		
		if(!empty($map['course_id'])){
		 	$map_sql .= " AND course_id='".$map['course_id']."'";
		 	$map_sql_list .= " AND p.course_id='".$map['course_id']."'";
		}
		
		
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	$this->assign('grade_id',$map['grade_id']);
    	$this->assign('course_id',$map['course_id']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$paper = new Model("Paper"); 
		/*if($map){
			$list = $paper->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $paper->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}*/
		$map_sql_list .= " ORDER BY p.ctime desc limit ".($cur_page-1)*$page_size.",".$page_size;
		$list = $paper->query($map_sql_list);
		
		$this->assign('paper_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $paper->where($map_sql)->count();
		}else{
			$count  = $paper->count();
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
    	$this->assign('source', array('1'=>'网络摘录','2'=>'教辅资料','3'=>'伊兔原创'));
    	$this->assign('arr_term', array('1'=>'第一学期','2'=>'第二学期'));
    	$this->assign('category', $this->getCategory());
    	if(!empty($map['grade_id'])){
			$grade_course = new Model("GradeCourse");
			$data_list = $grade_course->query("Select gc.course_id,c.title FROM grade_course gc LEFT JOIN course c ON gc.course_id=c.course_id WHERE gc.grade_id=".$map['grade_id']." ORDER BY c.course_id ASC ");
			
	    	$course_list = array();
	    	foreach($data_list as $key=>$val){
				$course_list[$val['course_id']] = $val['title'];
	    	}
			
			$this->assign('course_list',$course_list);
    	}
    	
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('category', $this->getCategory());
    	$this->assign('source', array('1'=>'网络摘录','2'=>'教辅资料','3'=>'伊兔原创'));
    	$this->assign('arr_term', array('1'=>'第一学期','2'=>'第二学期'));
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$paper = new Model("Paper");
    	$arr_form = $paper->query('SELECT * FROM paper WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	$this->assign('category', $this->getCategory());
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('source', array('1'=>'网络摘录','2'=>'教辅资料','3'=>'伊兔原创'));
    	$this->assign('arr_term', array('1'=>'第一学期','2'=>'第二学期'));
    	
    	$this->assign('value', $arr_form[0]['status']);
    	$this->assign('grade_id', $arr_form[0]['grade_id']);
		$this->assign('course_id', $arr_form[0]['course_id']);
    	$this->assign('term', $arr_form[0]['term']);
    	$this->assign('source_value', $arr_form[0]['source']);
    	
		$grade_course = new Model("GradeCourse");
		$data_list = $grade_course->query("Select gc.course_id,c.title FROM grade_course gc LEFT JOIN course c ON gc.course_id=c.course_id WHERE gc.grade_id=".$arr_form[0]['grade_id']." ORDER BY c.course_id ASC ");
		
    	$course_list = array();
    	foreach($data_list as $key=>$val){
			$course_list[$val['course_id']] = $val['title'];
    	}
		
		$this->assign('course_list',$course_list);
    	
    	
    	
		$this->display();
    }
    
    public function save(){
    	
    	$grade = $this->_param('grade_id');
    	$course = $this->_param('course_id');
    	
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728*5 ;// 设置附件上传大小
		$upload->allowExts  = array('doc', 'docx','jpg', 'jpeg', 'pdf', 'rar','zip');// 设置附件上传类型
			
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Paper/'.$grade.'/'.$course.'/';// 设置附件上传目录;
		if(!file_exists($up_path)){
			mkdir($up_path,0700);
		}
		
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
    	
		$paper = new Model("Paper");
		$paper->create();
		$paper->path =  '/Public/Upload/Paper/'.$grade.'/'.$course.'/'.$info[0]['savename'];
		$paper->size = $info[0]['size']/1024;
		$paper->ctime = date("Y-m-d H:i:s");
		$paper->add();
		
		//$msg = mb_convert_encoding("添加成功","UTF-8","GB2312");
    	//redirect("/Backend/Paper/index",2,$msg);
    	redirect("/Backend/Paper/index");
    }
    
    
    public function renew(){
		
		
		$paper = new Model("Paper");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['term'] = $this->_param('term');
		
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$source = $this->_param('source');
		$data['source'] = $source[0];
		$grade = $data['grade_id'] = $this->_param('grade_id');
		$course = $data['course_id'] = $this->_param('course_id');
				
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728*5 ;// 设置附件上传大小
		$upload->allowExts  = array('doc', 'docx','jpg', 'jpeg', 'pdf', 'rar','zip');// 设置附件上传类型
		//$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Paper/';// 设置附件上传目录;
		
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Paper/'.$grade.'/'.$course.'/';// 设置附件上传目录;
		//echo $up_path;die;
		if(!file_exists($up_path)){
			mkdir($up_path,0700);
		}
		
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			//$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		//print_r($info);die;
		if(!empty($info[0]['savename'])){
			$data['path'] = '/Public/Upload/Paper/'.$grade.'/'.$course.'/'.$info[0]['savename'];
			$data['size'] = $info[0]['size']/1024;
		}

		
		$paper->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Paper/index",1,$msg);
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$paper = new Model("Paper");
		$data = $paper->where("pkid=".$pkid)->select();
		
		$root_path = dirname(dirname(dirname(dirname(__FILE__))));
		$file_path = $root_path.$data[0]['path'];
		
		@unlink($file_path);
		$paper->query('DELETE FROM paper WHERE pkid='.$pkid);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Paper/index",1,$msg);
    	
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$paper = new Model("Paper");
		
		$paper->query("UPDATE paper SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Paper/index",1,$msg);
    	
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$paper = new Model("Paper");
		
		$paper->query("UPDATE paper SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Paper/index",1,$msg);
    	
    }
    
    public function getCategory(){
		$grade = new Model("Grade");
		$data_list = $grade->query("Select * FROM grade WHERE pkid>0 ORDER BY pkid ASC ");
    	$cate_list = array();
    	foreach($data_list as $key=>$val){
			$cate_list[$val['pkid']] = $val['title'];
    	}
    	return $cate_list; 
    }
    
	public function getcourse(){
		
		$grade_id = $_GET['grade_id'];
		
		$grade_course = new Model("GradeCourse");
		$data_list = $grade_course->query("Select gc.course_id,c.title FROM grade_course gc LEFT JOIN course c ON gc.course_id=c.course_id WHERE gc.grade_id=".$grade_id." ORDER BY c.course_id ASC ");
		
    	$course_list = array();
    	foreach($data_list as $key=>$val){
			$course_list[$val['course_id']] = $val['title'];
    	}
		
		$this->assign('course_list',$course_list);
		$this->display('course_select');
	}
    

}
?>