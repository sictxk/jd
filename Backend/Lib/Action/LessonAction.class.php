<?php

class LessonAction extends Action {
    public function index(){
    if(!$_SESSION['account']){
			redirect("/Backend/Index/index");
	}
    	$map = array();
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		$map['lectuer'] = !empty($_POST['lectuer']) ? $_POST['lectuer'] : (!empty($_GET['lectuer']) ? $_GET['lectuer'] : '');
		$map_sql = 'pkid>0 ';
		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		}
		if(!empty($map['lectuer'])){
		 	$map_sql .= " AND lectuer='".$map['lectuer']."'";
		}
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	$this->assign('lectuer',$map['lectuer']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$lesson = new Model("Lesson"); 
		if($map){
			$list = $lesson->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $lesson->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		
		
		$this->assign('lesson_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $lesson->where($map_sql)->count();
		}else{
			$count  = $lesson->count();
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
        $this->assign('grade_id', $this->gradeSet());
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$lesson = new Model("Lesson");
    	$arr_form = $lesson->where("pkid=".$pkid)->find();
    	
    	$this->assign('arr_form',$arr_form);

    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('grade_id', $this->gradeSet());
    	
    	$this->assign('grade_value', $arr_form['grade_id']);
        $this->assign('course_value', $arr_form['course_id']);
        $this->assign('status_value', $arr_form['status']);

        $this->assign('course_list', $this->_getCourse($arr_form['grade_id']));

		$this->display();
    }
    
    public function save(){

        import('ORG.Net.UploadFile');

        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Lesson/';// 设置附件上传目录;
        $upload->savePath =  $up_path;
        $upload->saveRule =  time();
        if($_FILES['book_cover']['name']!=''){
            if(!$upload->upload()) {// 上传错误提示错误信息
                $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
                $info =  $upload->getUploadFileInfo();
                $picture = '/Public/Upload/Lesson/'.$info[0]['savename'];
            }
        }
    	
		$lesson = new Model("Lesson");
		$lesson->create();
		$lesson->ctime = date("Y-m-d H:i:s");
        if($picture!=''){
            $lesson->book_cover = $picture;
        }
		$lesson->add();
		
    	redirect("/Backend/Lesson/index");
    }
    
    
    public function renew(){
		
	import('ORG.Net.UploadFile');

        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Lesson/';// 设置附件上传目录;
        $upload->savePath =  $up_path;
        $upload->saveRule =  time();
        if($_FILES['book_cover']['name']!=''){
            if(!$upload->upload()) {// 上传错误提示错误信息
                $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
                $info =  $upload->getUploadFileInfo();
                $picture = '/Public/Upload/Lesson/'.$info[0]['savename'];
            }
        }
    	
            $data['book_cover'] = $picture;
		$lesson = new Model("Lesson");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$data['lectuer'] = $this->_param('lectuer');
		$data['lectuer_intro'] = $this->_param('lectuer_intro');
	        $data['grade_id'] = $this->_param('grade_id');
	        $data['course_id'] = $this->_param('course_id');
	        $data['price'] = $this->_param('price');
        
		//print_r($data);die;
		$lesson->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Lesson/index",1,$msg);
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$lesson = new Model("Lesson");
		
		$lesson->query('DELETE FROM lesson WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Lesson/index",1,$msg);
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$lesson = new Model("Lesson");
		
		$lesson->query("UPDATE lesson SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Lesson/index",1,$msg);
    	//redirect("/Backend/Lesson/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$lesson = new Model("Lesson");
		
		$lesson->query("UPDATE lesson SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Lesson/index",1,$msg);
    	//redirect("/Backend/Lesson/index");
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

    public function _getCourse($grade_id){
        $course = new Model("Course");
        $data_list = $course->where("grade_id = ".$grade_id)->select();
        $course_list = array();
        if($data_list){
            foreach($data_list as $k=>$v){
                $course_list[$v['pkid']] = $v['title'];
            }
        }
        return $course_list;
    }
}
?>