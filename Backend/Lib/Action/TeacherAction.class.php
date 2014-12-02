<?php

class TeacherAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';

		$map['truename'] = !empty($_POST['truename']) ? $_POST['truename'] : (!empty($_GET['truename']) ? $_GET['truename'] : '');
		$map['code'] = !empty($_POST['code']) ? $_POST['code'] : (!empty($_GET['code']) ? $_GET['code'] : '');
		$map['area_id'] = !empty($_POST['area_id']) ? $_POST['area_id'] : (!empty($_GET['area_id']) ? $_GET['area_id'] : '');
		$map['service_mode'] = !empty($_POST['service_mode']) ? $_POST['service_mode'] : (!empty($_GET['service_mode']) ? $_GET['service_mode'] : '');
		$map['category_id'] = !empty($_POST['category_id']) ? $_POST['category_id'] : (!empty($_GET['category_id']) ? $_GET['category_id'] : '');
		$map['course_id'] = !empty($_POST['course_id']) ? $_POST['course_id'] : (!empty($_GET['course_id']) ? $_GET['course_id'] : '');

		$sql = "SELECT DISTINCT t.pkid,t.code,t.truename,t.email,t.mobile,t.service_mode,t.avatar,t.score FROM teacher t LEFT JOIN teacher_area ta ON ta.teacher_id=t.pkid ".
				" LEFT JOIN teacher_course tc ON tc.teacher_id=t.pkid  LEFT JOIN course c ON c.course_id=tc.course_id WHERE t.pkid>0 ";
		$sql_c = "SELECT COUNT(DISTINCT t.pkid) as num FROM teacher t LEFT JOIN teacher_area ta ON ta.teacher_id=t.pkid LEFT JOIN teacher_course tc ON tc.teacher_id=t.pkid ".
				" LEFT JOIN course c ON c.course_id=tc.course_id WHERE t.pkid>0 ";
		if(!empty($map['truename'])){
			$sql .= " AND u.truename like '%".$map['truename']."%'";
			$sql_c .= " AND u.truename like '%".$map['truename']."%'";
		}
		if(!empty($map['code'])){
		 	$sql .= " AND t.code  ='".$map['code']."'";
		 	$sql_c .= " AND t.code  ='".$map['code']."'";
		 }
		if(!empty($map['service_mode'])){
		 	$sql .= " AND t.service_mode  =".$map['service_mode'];
		 	$sql_c .= " AND t.service_mode  =".$map['service_mode'];
		 }
		if(!empty($map['area_id'])){
		 	$sql .= " AND ta.area_id  ='".$map['area_id']."'";
		 	$sql_c .= " AND ta.area_id  ='".$map['area_id']."'";
		 }
		if(!empty($map['course_id'])){
		 	$sql .= " AND tc.course_id  =".$map['course_id'];
		 	$sql_c .= " AND tc.course_id  =".$map['course_id'];
		 }else{
			if(!empty($map['category_id'])){
			 	$sql .= " AND c.category_id  =".$map['category_id'];
			 	$sql_c .= " AND c.category_id  =".$map['category_id'];
			 }
		 }
		 
    	$this->assign('map',$map);
    	foreach($map as $ke=>$va ){
			$this->assign($ke,$va);
    	}
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 10;
		
		$teacher = new Model("Teacher");
		
		$sql .= " ORDER BY t.ctime desc limit ".($cur_page-1)*$page_size.",".$page_size;
		$list = $teacher->query($sql);
		$teacher_area = new Model("TeacherArea");
		foreach($list as $key=>$val){
			$list[$key]['area_list'] = $teacher_area->query("SELECT pc.name FROM teacher_area ta LEFT JOIN province_city pc ON ta.area_id=pc.item_id WHERE ta.teacher_id=".$val['pkid']);
            $list[$key]['course_list'] = $teacher_area->query("SELECT cat.title as cat_title,c.title as course_title FROM teacher_course tc LEFT JOIN course c ON tc.course_id=c.course_id LEFT JOIN category cat ON c.category_id=cat.pkid WHERE tc.teacher_id=".$val['pkid']);
		}

		$this->assign('teacher_list',$list);
		import("ORG.Util.Page");
		
		$count_num = $teacher->query($sql_c);//print_r($count_num);
		$count = $count_num[0]['num'];
		
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
    	
    	
    	$data_area_list = $this->getArea(3101);
    	foreach($data_area_list as $k=>$v){
    		$area_list[$v['pkid']] = $v['name'];
    	}
    	$this->assign('area_list',$area_list);
    	
    	$category_list = $this->getCategorySelect();
    	$this->assign('category_list',$category_list);
    	
		$service_mode = array('1'=>'教师上门','2'=>'学生上门');
		$this->assign('arr_service_mode',$service_mode);   

        if($map['category_id']!=''){
            $course = new Model("Course");
            $sql = "SELECT * FROM course WHERE category_id=".$map['category_id'];
            $data_course = $course->query($sql);
            foreach($data_course as $key=>$val){
                $course_list[$val['course_id']] = $val['title'];
            }
            $this->assign('course_list',$course_list);
        }

		$this->display();
    }
    

    
    public function add(){
    	
		$this->assign('city_list', $this->cityList());
		
		$service_mode = array('1'=>'教师上门','2'=>'学生上门');
		$this->assign('arr_service_mode',$service_mode);    	
		$this->display();
    }
    
    public function edit(){
    	
    	$tab = $this->_param('tab') ? $this->_param('tab'):0;
    	
    	$pkid = $this->_param('pkid');
    	$teacher = new Model("Teacher");
    	$arr_form = $teacher->query('SELECT * FROM teacher WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
		$service_mode = array('1'=>'教师上门','2'=>'学生上门');
		$this->assign('arr_service_mode',$service_mode);    
		
		
		$teacher_area = $this->getTeacherArea($pkid);
		
		$area_list = $this->getArea($arr_form[0]['city_id']);
		foreach($area_list as $k=>$v){
			if(in_array($v['item_id'],$teacher_area)){
				$area_list[$k]['checked'] = "checked";
			}
		}
		$this->assign('area_list', $area_list);
		$this->assign('city_list', $this->cityList());
    	$this->assign('city_id', $arr_form[0]['city_id']);
    	
    	//授课科目
    	$teacher_id = $pkid;
    	$category = new Model("Category");
    	$sql = "SELECT cat.pkid FROM teacher_course tc LEFT JOIN course c ON tc.course_id=c.course_id ".
    			" LEFT JOIN category cat ON c.category_id=cat.pkid WHERE tc.teacher_id=".$teacher_id;
    	$res1 = $category->query($sql);
    	
    	$sql2 = "SELECT pkid,title as cat_title FROM category WHERE pkid>0 ";
    	if(!empty($res1[0]['pkid'])){
    		$sql2 .=" AND pkid NOT IN( $sql )";
    	}
    	$data_category_list = $category->query($sql2);
		
		$course = new Model("Course");
		if(!empty($data_category_list)){
			foreach($data_category_list as $key=>$val){
				$data_category_list[$key]['course_id'] = '';
				$data_category_list[$key]['hourly_pay'] = '';
				$data_category_list[$key]['course_list'] = $course->query('SELECT course_id,title FROM course WHERE category_id='.$val['pkid']);

			}
		}
    	$teacher_course = new Model("TeacherCourse");
    	$sql = "SELECT cat.pkid,cat.title as cat_title,tc.course_id,tc.hourly_pay,c.title FROM teacher_course tc LEFT JOIN course c ON tc.course_id=c.course_id ".
    			" LEFT JOIN category cat ON c.category_id=cat.pkid WHERE tc.teacher_id=".$teacher_id;
    	$data_list_course = $teacher_course->query($sql);//print_r($data_list_course);
    	if(!empty($data_list_course)){
			foreach($data_list_course as $key=>$val){
				if($val['pkid']!=''){
					$data_list_course[$key]['course_list'] = $course->query('SELECT course_id,title FROM course WHERE category_id='.$val['pkid']);
				}
			}
		}

		$category_list = array_merge($data_category_list,$data_list_course);
		
    	$this->assign('category_list',$category_list);
    	
    	
    	//授课时间
		//初始化授课时间表
		//$this->initTeacherSchedule($teacher_id);
		
    	$teacher_schedule = new Model('TeacherSchedule');
    	$data_schedule = $teacher_schedule->where('teacher_id='.$teacher_id)->select();
    	
    	$list_schedule = array();
    	foreach($data_schedule as $val){
    		$list_schedule[$val['weekday']][$val['period']] = $val;
    	}
    	//print_r($list_schedule);
    	$this->assign('list_schedule',$list_schedule);
    	
    	
    	$this->assign('tab',$tab);
    	
    	
		$this->display();
    }
    
    public function save(){
    	
		$teacher = new Model("Teacher");
		
		$teacher->create();
		$teacher->ctime = date("Y-m-d H:i:s");
		$code = 'T'.date("ymdHi");
		$teacher->code = $code;
		$teacher->rating = 10;
		$service_mode = $this->_param('service_mode');
		$teacher->service_mode = $service_mode[0];
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 1048576 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Avatar/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if($_FILES['avatar']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$teacher->avatar = '/Public/Upload/Avatar/'.$info[0]['savename'];
				//图片裁切为指定尺寸
				import('ORG.Util.Image'); 
				$img = new Image; 
				$img->thumb($up_path.$info[0]['savename'],$up_path."100_".$info[0]['savename'],'',100, 100,true);
			}
		}
		$teacher->add();
		
		$data = $teacher->query("SELECT * FROM teacher WHERE code='".$code."'");
		$teacher_id = $data[0]['pkid'];
			
		$teacher_area  = new Model('TeacherArea');
		$teacher_area->query("DELETE FROM teacher_area WHERE teacher_id=".$teacher_id);
		
		$arr_area = $this->_param('area_id');
	
		foreach($arr_area as $v){
			$teacher_area->create();
			$teacher_area->teacher_id = $teacher_id;
			$teacher_area->city_id = $data[0]['city_id'];
			$teacher_area->area_id = $v;
			$teacher_area->ctime = date("Y-m-d H:i:s");
			$teacher_area->add();
		}
		
		//初始化授课时间表
		$this->initTeacherSchedule($teacher_id);
		
    	redirect("/Backend/Teacher/index");
    }
    
    
    public function renew(){
    	
		$teacher = new Model("Teacher");
		$data['pkid'] = $this->_param('pkid');
		$data['truename'] = $this->_param('truename');
		$data['email'] = $this->_param('email');
		$data['mobile'] = $this->_param('mobile');
		$data['alipay'] = $this->_param('alipay');
		$data['city_id'] = $this->_param('city_id');
		$data['description'] = $this->_param('description');

		$service_mode = $this->_param('service_mode');
		$data['service_mode'] = $service_mode[0];
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 1048576 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Avatar/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if($_FILES['avatar']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$data['avatar'] = '/Public/Upload/Avatar/'.$info[0]['savename'];
				//图片裁切为指定尺寸
				import('ORG.Util.Image'); 
				$img = new Image; 
				$img->thumb($up_path.$info[0]['savename'],$up_path."100_".$info[0]['savename'],'',100, 100,true);
			}
		}
		$teacher->save($data);
		
		//授课区域
		$teacher_area  = new Model('TeacherArea');
		$teacher_area->query("DELETE FROM teacher_area WHERE teacher_id=".$data['pkid']);
		$arr_area = $this->_param('area_id');
		
		foreach($arr_area as $v){
			$teacher_area->create();
			$teacher_area->pkid = '';
			$teacher_area->teacher_id = $data['pkid'];
			$teacher_area->city_id = $data['city_id'];
			$teacher_area->area_id = $v;
			$teacher_area->ctime = date("Y-m-d H:i:s");
			$teacher_area->add();
		}
		
		//授课科目
    	$teacher_course = new Model("TeacherCourse");
		$teacher_id = $data['pkid'];
		
    	$list_cate = $this->getCategoryList();
    	$data_list = array();
    	foreach($list_cate as $cat){
    		if($this->_param('category_id_'.$cat)){
    			$unit = array();
    			$unit['course_id'] = $this->_param('course_id_'.$cat);
    			$unit['hourly_pay'] = $this->_param('hourly_pay_'.$cat);
    			//$unit['promote_price'] = $this->_param('promote_price_'.$cat);

    			$data_list[$cat] = $unit;
    		}
    	}
		foreach($data_list as $key=>$cat){
			$size = sizeof($cat['course_id']);
			for($i=0;$i<$size;$i++){
				if($cat['course_id'][$i]=='' || $cat['hourly_pay'][$i]=='' ){
					$msg = mb_convert_encoding("请将所有勾选类别的科目、时薪填写完整","GB2312","UTF-8");
					print "<script language=\"javascript\">alert('$msg');location.href='/Backend/Teacher/edit/pkid/$teacher_id/tab/2';</script>";
					exit;
				}
			}
		}
		$teacher_course->query("delete FROM teacher_course where teacher_id=".$teacher_id);
		foreach($data_list as $key=>$cat){
			$size = sizeof($cat['course_id']);
			$arr_course_has = array();
			for($i=0;$i<$size;$i++){
				$course_id = $cat['course_id'][$i];
				if(!in_array($course_id,$arr_course_has)){
					$teacher_course->create();
					$teacher_course->pkid = '';
					$teacher_course->teacher_id = $teacher_id;
					$teacher_course->course_id = $course_id;
					$teacher_course->hourly_pay = $cat['hourly_pay'][$i];
					//$teacher_course->promote_price = $cat['promote_price'][$i];
					$teacher_course->add();
					
					$arr_course_has[] = $cat['course_id'][$i];
				}
			}
		}
		//授课时间
    	$weekday = $this->_param('weekday');    	
    	$list_schedule = array();
    	for($i=1;$i<8;$i++){
    		$unit['teacher_id'] = $teacher_id;
    		$unit['weekday'] = $i;
    		if(in_array($i,$weekday)){
    			$unit['status'] = 1;
    		}else{
    			$unit['status'] = 2;
    		}
    		
    		$unit_am = $unit_pm = $unit_night = $unit;
    		$unit_am['period'] = 1;
    		$unit_am['start_time'] = $this->_param('weekday'.$i.'_am_start');
    		$unit_am['end_time'] = $this->_param('weekday'.$i.'_am_end');
    		
    		$unit_pm['period'] = 2;
  			$unit_pm['start_time'] = $this->_param('weekday'.$i.'_pm_start');
    		$unit_pm['end_time'] = $this->_param('weekday'.$i.'_pm_end');
    		
    		$unit_night['period'] = 3;
  			$unit_night['start_time'] = $this->_param('weekday'.$i.'_night_start');
    		$unit_night['end_time'] = $this->_param('weekday'.$i.'_night_end');
    		$list_schedule[] = $unit_am;
    		$list_schedule[] = $unit_pm;
    		$list_schedule[] = $unit_night;
    	}
    	
    	$teacher_schedule = new Model('TeacherSchedule');
		foreach($list_schedule as $v){
			$data = array();
			$data['start_time'] = $v['start_time'];
			$data['end_time'] = $v['end_time'];
			$data['status'] = $v['status'];
			
			$condition['teacher_id'] = $v['teacher_id'];
			$condition['weekday'] = $v['weekday'];
			$condition['period'] = $v['period'];
			
			$row_schedule = $teacher_schedule->where($condition)->select();
			$data['pkid'] = $row_schedule[0]['pkid'];
			$teacher_schedule->save($data);
			
		}
		
    	redirect("/Backend/Teacher/index");
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$teacher = new Model("Teacher");
		//进行原生的SQL查询
		$teacher->query('DELETE FROM teacher WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Teacher/index",1,$msg);
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$teacher = new Model("Teacher");
		//进行原生的SQL查询
		$teacher->query("UPDATE teacher SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Teacher/index",1,$msg);
    }
    
	
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$teacher = new Model("Teacher");
		//进行原生的SQL查询
		$teacher->query("UPDATE teacher SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Teacher/index",1,$msg);
		
    }
    
    private function initTeacherSchedule($teacher_id){
    	
    	$weekday = $this->_param('weekday') ? $this->_param('weekday') : array(1,2,3,4,5,6,7);
    	$list_schedule = array();
    	for($i=1;$i<8;$i++){
    		$unit['teacher_id'] = $teacher_id;
    		$unit['weekday'] = $i;
    		if(in_array($i,$weekday)){
    			$unit['status'] = 1;
    		}else{
    			$unit['status'] = 2;
    		}
    		
    		$unit_am = $unit_pm = $unit_night = $unit;
    		$unit_am['period'] = 1;
    		$unit_am['start_time'] = $this->_param('weekday'.$i.'_am_start');
    		$unit_am['end_time'] = $this->_param('weekday'.$i.'_am_end');
    		
    		$unit_pm['period'] = 2;
  			$unit_pm['start_time'] = $this->_param('weekday'.$i.'_pm_start');
    		$unit_pm['end_time'] = $this->_param('weekday'.$i.'_pm_end');
    		
    		$unit_night['period'] = 3;
  			$unit_night['start_time'] = $this->_param('weekday'.$i.'_night_start');
    		$unit_night['end_time'] = $this->_param('weekday'.$i.'_night_end');
    		$list_schedule[] = $unit_am;
    		$list_schedule[] = $unit_pm;
    		$list_schedule[] = $unit_night;
    	}
    	
    	$teacher_schedule = new Model('TeacherSchedule');
    	
    	$data_schedule = $teacher_schedule->where('teacher_id='.$teacher_id)->select();
    	if(empty($data_schedule)){
    		$exec_flag = 'add';
    	}
    		
		foreach($list_schedule as $v){
			
			if($exec_flag == 'add'){
				$teacher_schedule->create();
				$teacher_schedule->teacher_id = $v['teacher_id'];
				$teacher_schedule->weekday = $v['weekday'];
				$teacher_schedule->period = $v['period'];
				
				$teacher_schedule->start_time = $v['start_time'];
				$teacher_schedule->end_time = $v['end_time'];
				$teacher_schedule->status = $v['status'];
				$teacher_schedule->ctime = date('Y-m-d H:i:s');
				$teacher_schedule->add();
			}else{
				$data = array();
				$data = $v;
				
				$condition['teacher_id'] = $v['teacher_id'];
				$condition['weekday'] = $v['weekday'];
				$condition['period'] = $v['period'];
				
				$row_schedule = $teacher_schedule->where($condition)->select();
				$data['pkid'] = $row_schedule[0]['pkid'];
					
				$teacher_schedule->save($data);
				
			}
		}
    	
    }
    
    
	private function getArea($city_id){
		
		$list = array();
		if($city_id!=''){
			$ProvinceCity = D('ProvinceCity');
			$list = $ProvinceCity->where("parent_id=".$city_id)->select();	
		}
		
		return $list;
    }
    
	private function cityList(){
		$city = D('ProvinceCity');
		$sql = "select item_id,name FROM province_city where level=2 and status=1 order by item_id asc ";
		$data_list = $city->query($sql);
		foreach($data_list as $key=>$val){
			$city_list[$val['item_id']] = $val['name'];
		}
		
		return $city_list;
    }
    
	private function getTeacherArea($tid){
		
		$teacher_area = new Model('TeacherArea');
		$sql = 'SELECT ta.area_id FROM teacher_area ta  WHERE ta.teacher_id='.$tid;
		$list = $teacher_area->query($sql);
		

		$ta_list = array();
		foreach($list as $k=>$v){
			$ta_list[] = $v['area_id'];
		}

		return $ta_list;
    }
    
	private function getTeacherAreaById($tid,$limit){
		
		$teacher_area = new Model('TeacherArea');
		$sql = "SELECT ta.area_id,a.name FROM teacher_area ta LEFT JOIN area a ON ta.area_id=a.area_id WHERE ta.teacher_id=".$tid;
		if($limit){
			$sql .= " limit 0,".$limit;
		}
		$list = $teacher_area->query($sql);
		
		return $list;
    }
    
    
    private function getCategoryList(){
    	
    	$category = new Model("Category");
    	$category_list = $category->query("SELECT pkid,title FROM category");
    	
    	$list_cate = array();
    	foreach($category_list as $key=>$val){
    		$list_cate[] = $val['pkid'];
    	}
    	
    	return $list_cate;
    	
    }
    
    private function getCategorySelect(){
    	
    	$category = new Model("Category");
    	$category_list = $category->query("SELECT pkid,title FROM category");
    	
    	$list_cate = array();
    	foreach($category_list as $key=>$val){
    		$list_cate[$val['pkid']] = $val['title'];
    	}
    	
    	return $list_cate;
    	
    }
}
?>