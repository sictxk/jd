<?php

class CourseAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';
		
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
        $map['grade_id'] = !empty($_POST['grade_id']) ? $_POST['grade_id'] : (!empty($_GET['grade_id']) ? $_GET['grade_id'] : '');
		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }
        if(!empty($map['grade_id'])){
            $map_sql .= " AND grade_id=".$map['grade_id'];
        }
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	$this->assign('grade_value',$map['grade_id']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 15;
		$course = new Model("Course");

		if($map){
			$list = $course->where($map_sql)->order('create_time desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $course->order('create_time desc')->page($cur_page.",".$page_size)->select();
		}
		$arr_grade = $this->gradeSet();
		$this->assign('arr_grade', $arr_grade);
		
		foreach($list as $k=>$v){
			$list[$k]['grade'] = $arr_grade[$v['grade_id']];
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
    	
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('grade_id', $this->gradeSet());

    			
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
		$this->assign('grade_id', $this->gradeSet());
		
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$course = new Model("Course");
    	$arr_form = $course->query('SELECT * FROM course WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('value', $arr_form[0]['status']);
    	
    	$this->assign('grade_id', $this->gradeSet());
    	$this->assign('grade_value', $arr_form[0]['grade_id']);
		
		$this->display();
    }
    
    public function save(){
    	
		$course = new Model("Course");
		
		$course->create();
		
		$course->create_time = date('Y-m-d H:i:s');
		$course->add();
		
		//$msg = mb_convert_encoding("添加成功","UTF-8","GB2312");
    	//redirect("/Backend/Course/index",2,$msg);
    	redirect("/Backend/Course/index");
    }
    
    
    public function renew(){
    	
		$course = new Model("Course");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$data['grade_id'] = $this->_param('grade_id');
		$course->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Course/index",2,$msg);
    	redirect("/Backend/Course/index");
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$course = new Model("Course");
		
		$course->query('DELETE FROM course WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Course/index",1,$msg);
    	//redirect("/Backend/Course/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$course = new Model("Course");

		$course->query("UPDATE course SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Course/index",1,$msg);
    	//redirect("/Backend/Course/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$course = new Model("Course");

		$course->query("UPDATE course SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Course/index",1,$msg);
    	//redirect("/Backend/Course/index");
    }
    
	private function gradeSet(){
		$Grade = D('Grade');
		$arr_grade =  $Grade->where('pkid > 0')->select();
		
		$arr_select = array();
		foreach($arr_grade as $key=>$val){
			$arr_select[$val['pkid']] = $val['title'];
		}

		return $arr_select;
    }


    public function getCourse(){

        $grade_id = $_GET['grade_id'];
        $course = new Model("Course");
        $data_list = $course->where("grade_id = ".$grade_id)->select();
        $course_list = array();
        if($data_list){
            foreach($data_list as $k=>$v){
                $course_list[$v['pkid']] = $v['title'];
            }
        }
        $this->assign('course_list',$course_list);
        $this->display('course_select');

    }

    public function getCourseCheckbox(){

        $grade_id = $_GET['grade_id'];
        $course = new Model("Course");
        $data_list = $course->where("grade_id = ".$grade_id)->select();
        $this->assign('course_list',$data_list);
        $this->display('course_checkbox');

    }
}
?>