<?php

class AgencyCourseAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';
		
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		$map['category_id'] = !empty($_POST['category_id']) ? $_POST['category_id'] : (!empty($_GET['category_id']) ? $_GET['category_id'] : '');
		$map['second_id'] = !empty($_POST['second_id']) ? $_POST['second_id'] : (!empty($_GET['second_id']) ? $_GET['second_id'] : '');
		$map['third_id'] = !empty($_POST['third_id']) ? $_POST['third_id'] : (!empty($_GET['third_id']) ? $_GET['third_id'] : '');
		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }
		if(!empty($map['category_id'])){
		 	$map_sql .= " AND category_id='".$map['category_id']."'";
		 }
		if(!empty($map['second_id'])){
		 	$map_sql .= " AND second_id='".$map['second_id']."'";
		 	
		 }
		if(!empty($map['third_id'])){
		 	$map_sql .= " AND third_id='".$map['third_id']."'";
		 }
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	$this->assign('category_value',$map['category_id']);
    	$this->assign('second_value',$map['second_id']);
    	$this->assign('third_value',$map['third_id']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 14;
		$course = new Model("AgencyCourse");

		if($map){
			$list = $course->where($map_sql)->order('create_time desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $course->order('create_time desc')->page($cur_page.",".$page_size)->select();
		}
		$arr_category = $this->categorySet();

		$this->assign('arr_category', $arr_category);
		$arr_all_category = $this->categoryAllSet();
		foreach($list as $k=>$v){
			$list[$k]['category'] = $arr_category[$v['category_id']];
			$list[$k]['second_category'] = $arr_all_category[$v['second_id']];
			$list[$k]['third_category'] = $arr_all_category[$v['third_id']];
		}
		
		$this->assign('course_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $course->where($map_sql)->count();
		}else{
			$count  = $course->count();
		}
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
		$this->assign('pno',$cur_page);
    	
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	if($map['category_id']){
    		$this->assign('second_list',$this->getSubCategory($map['category_id']));
    	}
    	if($map['second_id']){
    		$this->assign('third_list',$this->getSubCategory($map['second_id']));
		}
    			
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
		$this->assign('category_id', $this->categorySet());
		
		$this->display();
    }
    
    public function edit(){
    	$course_id = $this->_param('pkid');
    	$_SESSION['agencyPno'] = $this->_param('pno');
    	$course = new Model("AgencyCourse");
    	$arr_form = $course->query('SELECT * FROM agency_course WHERE pkid='.$course_id);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('value', $arr_form[0]['status']);
    	
    	$this->assign('category_id', $this->categorySet());
    	$this->assign('category_value', $arr_form[0]['category_id']);
    	if($arr_form[0]['category_id']!=''){
    		$this->assign('second_list',$this->getSubCategory($arr_form[0]['category_id']));
    	}
    	if($arr_form[0]['second_id']!=''){
    		$this->assign('third_list',$this->getSubCategory($arr_form[0]['second_id']));
    	}
    	$this->assign('second_value', $arr_form[0]['second_id']);
    	$this->assign('third_value', $arr_form[0]['third_id']);
		
		$this->display();
    }
    
    public function save(){
    	
		$course = new Model("AgencyCourse");
		
		$course->create();
		$course->create_time = date('Y-m-d H:i:s');
		$course->add();
		
    	redirect("/Backend/AgencyCourse/index");
    }
    
    
    public function renew(){
    	
		$course = new Model("AgencyCourse");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$data['category_id'] = $this->_param('category_id');
		$data['second_id'] = $this->_param('second_id');
		$data['third_id'] = $this->_param('third_id');
		
		$course->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCourse/index/p/".$_SESSION['agencyPno']);
    }
    
    
    public function remove(){
    	
    	$course_id = $this->_param('pkid'); 
    	
		$course = new Model("AgencyCourse");
		
		$course->query('DELETE FROM agency_course WHERE pkid='.$course_id);
    	
		$agency_bind_course = new Model("AgencyBindCourse");
		
		$agency_bind_course->query('DELETE FROM agency_bind_course WHERE course_id='.$course_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCourse/index/p/".$_SESSION['agencyPno'],1,$msg);
    	//redirect("/Backend/AgencyCourse/index");
    }
    
    public function hide(){
    	
    	$course_id = $this->_param('pkid'); 
    	
		$course = new Model("AgencyCourse");

		$course->query("UPDATE agency_course SET status='N' WHERE pkid=".$course_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCourse/index",1,$msg);
    	//redirect("/Backend/AgencyCourse/index");
    }
    
    public function show(){
    	
    	$course_id = $this->_param('pkid'); 
    	
		$course = new Model("AgencyCourse");

		$course->query("UPDATE agency_course SET status='Y' WHERE pkid=".$course_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCourse/index",1,$msg);
    	//redirect("/Backend/AgencyCourse/index");
    }
    
	private function categorySet(){
		$AgencyCategory = D('AgencyCategory');
		$arr_category =  $AgencyCategory->where('pkid > 0 and level=1')->select();
		
		$arr_select = array();
		foreach($arr_category as $key=>$val){
			$arr_select[$val['pkid']] = $val['title'];
		}

		return $arr_select;
    }
    
	private function categoryAllSet(){
		$AgencyCategory = D('AgencyCategory');
		$arr_category =  $AgencyCategory->where('pkid > 0 ')->select();
		
		$arr_select = array();
		foreach($arr_category as $key=>$val){
			$arr_select[$val['pkid']] = $val['title'];
		}

		return $arr_select;
    }
    
    /*
    * 获取子分类
    */
    public function getSubCategory($parent_id){
		$agency_category = new Model("AgencyCategory");
		//进行原生的SQL查询
		$data_list = $agency_category->query("Select * FROM agency_category WHERE parent_id=".$parent_id." ORDER BY pkid ASC ");
    	foreach($data_list as $key=>$val){
    		$cate_list[$val['pkid']] = $val['title'];
    	}
    	return $cate_list;
    }
    
}
?>