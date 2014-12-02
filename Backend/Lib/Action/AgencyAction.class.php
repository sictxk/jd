<?php

class AgencyAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';

        $map['code'] = !empty($_POST['code']) ? $_POST['code'] : (!empty($_GET['code']) ? $_GET['code'] : '');
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		$map['brand'] = !empty($_POST['brand']) ? $_POST['brand'] : (!empty($_GET['brand']) ? $_GET['brand'] : '');

        if(!empty($map['code'])){
            $map_sql .= "AND code like '%".$map['code']."%'";
        }
		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }
		 
		if(!empty($map['brand'])){
		 	$map_sql .= " AND brand_id in (select pkid from agency_brand ab where ab.title like '%".$map['brand']."%') ";
		 }
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);

    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 6;
		$agency = new Model("Agency");

		if($map){
			$list = $agency->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $agency->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}

        $brand_list = $this->get_brand();
        $area_list = $this->areaList('3101');
        $this->assign('area_list', $area_list);

        $agency_category = new Model("AgencyCategory");
		foreach($list as $k=>$v){
            $list[$k]['brand_name'] = $brand_list[$v['brand_id']];
            $list[$k]['area_name'] = $area_list[$v['area_id']];
            if($v['category_id']!=''){
                $list[$k]['category_list'] = $agency_category->query("SELECT title FROM agency_category WHERE pkid in(".$v['category_id'].")");
            }
		}

		$this->assign('agency_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $agency->where($map_sql)->count();
		}else{
			$count  = $agency->count();
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
    	$this->assign('join_best', array('1'=>'是','2'=>'否'));
		
    			
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('sign_type', array('1'=>'已入驻','2'=>'未入驻'));
    	$this->assign('promotion_type', array('1'=>'折扣','2'=>'现金'));
    	$this->assign('join_best', array('1'=>'是','2'=>'否'));
    	$this->assign('discount_rate', array('3'=>'3折','4'=>'4折','5'=>'5折','6'=>'6折','7'=>'7折','8'=>'8折','9'=>'9折'));
		$this->assign('brand', $this->get_brand());
        //$this->assign('category', $this->get_category());
        $this->assign('city_list', $this->cityList());

		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
        $tab_num = $this->_param('tab') ? $this->_param('tab') : 0;
        $pno = $this->_param('pno') ? $this->_param('pno') : 1;

    	$agency = new Model("Agency");
    	$arr_form = $agency->query('SELECT * FROM agency WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
        //$this->assign('category', $this->get_category());
        $this->assign('brand', $this->get_brand());
        $this->assign('city_list', $this->cityList());

        $this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('sign_type', array('1'=>'已入驻','2'=>'未入驻'));
    	$this->assign('promotion_type', array('1'=>'折扣','2'=>'现金'));
    	$this->assign('join_best', array('1'=>'是','2'=>'否'));
    	$discount_rate = array('3'=>'3折','4'=>'4折','5'=>'5折','6'=>'6折','7'=>'7折','8'=>'8折','9'=>'9折');
    	$this->assign('discount_rate', $discount_rate);

    	$this->assign('tab_num', $tab_num);
        $this->assign('pno', $pno);
    	$this->assign('value', $arr_form[0]['status']);
    	$this->assign('join_value', $arr_form[0]['join_best']);
        $this->assign('city_id', $arr_form[0]['city_id']);
        $this->assign('brand_id', $arr_form[0]['brand_id']);
        $this->assign('category_id', explode(",",$arr_form[0]['category_id']));

        $this->assign('area_list', $this->areaList($arr_form[0]['city_id']));
        $this->assign('area_id', $arr_form[0]['area_id']);
    	//$this->assign('value', $arr_form[0]['status']);
        $this->assign('type_value', $arr_form[0]['sign_type']);
		$this->assign('promotion_value', $arr_form[0]['promotion_type']);
		$this->assign('discount_value', $arr_form[0]['discount_rate']);

        $agency_picture = new Model("AgencyPicture");
        $album_list = $agency_picture->query('SELECT * FROM agency_picture WHERE agency_id='.$pkid);
        $this->assign('album_list', $album_list);


    	//授课科目
    	$agency_id = $pkid;
    	$category = new Model("AgencyCategory");
    	$sql = "SELECT cat.pkid FROM agency_bind_course abc LEFT JOIN agency_course c ON abc.course_id=c.pkid ".
    			" LEFT JOIN agency_category cat ON c.category_id=cat.pkid WHERE abc.agency_id=".$agency_id;
    	$res1 = $category->query($sql);
    	
    	$sql2 = "SELECT pkid,title as cat_title FROM agency_category WHERE pkid>0 and level=1";
    	if(!empty($res1[0]['pkid'])){
    		$sql2 .=" AND pkid NOT IN( $sql )";
    	}
    	$data_category_list = $category->query($sql2);
		
		$course = new Model("AgencyCourse");
		if(!empty($data_category_list)){
			foreach($data_category_list as $key=>$val){
				$data_category_list[$key]['course_list'] = $course->query('SELECT pkid,title FROM agency_course WHERE category_id='.$val['pkid']);

			}
		}
		
    	$agency_bind_course = new Model("AgencyBindCourse");
    	$sql = "SELECT abc.id,c.category_id,(SELECT cat.title FROM agency_category cat WHERE cat.pkid=c.second_id) as second_title,".
    			" (SELECT cat.title FROM agency_category cat WHERE cat.pkid=c.third_id) as third_title, c.title as course_title ".
    			" FROM agency_bind_course abc LEFT JOIN agency_course c ON abc.course_id=c.pkid WHERE abc.agency_id= ".$pkid;
    	$data_list_course = $agency_bind_course->query($sql);//print_r($data_list_course);
    	
    	/*if(!empty($data_list_course)){
			foreach($data_list_course as $key=>$val){
				if($val['pkid']!=''){
					$data_list_course[$key]['course_list'] = $course->query('SELECT pkid,title FROM agency_course WHERE category_id='.$val['pkid']);
				}
			}
		}

		$category_list = array_merge($data_category_list,$data_list_course);
		foreach($category_list as $key=>$val){
			$category_list[$key]['second_list'] = $this->getSubCategory($val['pkid']);
		}*/
		
    	//$this->assign('category_list',$category_list);
    	
		$this->assign('arr_category', $this->categorySet());
		$this->assign('data_list_course', $data_list_course);
		$this->display();
    }
    
    public function save(){
		$agency = new Model("Agency");
		
		import('ORG.Net.FileManage');
		$upload_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Agency/';
		if ($_FILES['picture']['name'] ) {//展示图
			$filemanage = new FileManage($_FILES['picture'], $upload_path );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$picture = '/Public/Upload/Agency/'.date('Ym').'/'. basename($arr_img['path']);
			} else {
				$this->error($filemanage->errormsg);
			}
		}
		
		$agency->create();
		if($picture!=''){
			$agency->picture = $picture;
		}
		$agency->category_id = implode(",",$this->_param('category_id'));
        $ctime = date('Y-m-d H:i:s');
		$agency->ctime = $ctime;
		

        $sign_type = $this->_param('sign_type');
        $agency->sign_type = $sign_type[0] ? $sign_type[0] : 2;
        $promotion_type = $this->_param('promotion_type');
        $agency->promotion_type = $promotion_type[0] ? $promotion_type[0] : '';
        $join_best = $this->_param('join_best');
        $agency->join_best = $join_best[0] ? $join_best[0] : '';
		$agency->discount_rate = $this->_param('discount_rate');
		$agency->rebate_cash = $this->_param('rebate_cash');
		$agency->province_id = 31;
		$agency->city_id = $this->_param('city_id');
		$agency->area_id = $this->_param('area_id');
		
		$agency->add();

        //get agency pkid
        $agency = new Model("Agency");
        $data_agency = $agency->query("SELECT * FROM agency WHERE ctime='".$ctime."'");
        $agency_id = $data_agency[0]['pkid'];
		
		$code = 'A'.str_pad($agency_id, 4, "0", STR_PAD_LEFT);
		$pass = md5('123456');
		$agency->query("UPDATE agency set code='".$code."',pass='".$pass."' WHERE pkid=".$agency_id);
		
		//相册上传
		if ($_FILES['album']) {
		   $file_ary = $this->reArrayFiles($_FILES['album']);//print_r($file_ary);
		   $image_form = array();
		   foreach ($file_ary as $file) {
				$filemanage = new FileManage($file, $upload_path );
				if ( $filemanage->upload() ) {
					$arr_image['path'] = $filemanage->get_file_path();
					$arr_path = '/Public/Upload/Agency/'.date('Ym').'/'.basename($arr_image['path']);
					$image_form[] = $arr_path;
				}
		   }
		   
		   $arr_img_title = $this->_param('img_title');
		   
		   //写入数据库
		   $agency_picture = new Model("AgencyPicture");
		   foreach($image_form as $key=>$img){
		   	   $agency_picture->create();
		   	   $agency_picture->pkid = '';
		   	   $agency_picture->agency_id = $agency_id;
		   	   $agency_picture->title = $arr_img_title[$key];
		   	   $agency_picture->picture = $img;
		   	   $agency_picture->status = 'Y';
		   	   $agency_picture->ctime = date('Y-m-d H:i:s');
			   $agency_picture->add();
		   }
		}
		
		
		//复制科目表
	
		$exist_course = $agency->query("SELECT distinct abc.course_id FROM agency_bind_course abc LEFT JOIN agency a ON abc.agency_id=a.pkid ".
						"WHERE a.brand_id=".$data_agency[0]['brand_id']." AND a.pkid!=".$agency_id);
		if(!empty($exist_course)){
			$agency_bind_course = new Model("AgencyBindCourse");
			foreach($exist_course as $k=>$v){
					$agency_bind_course->create();
					$agency_bind_course->pkid = '';
					$agency_bind_course->agency_id = $agency_id;
					$agency_bind_course->course_id = $v['course_id'];
					$agency_bind_course->ctime = date("Y-m-d H:i:s");
					$agency_bind_course->add();
			}
		}
    	redirect("/Backend/Agency/index");
    }
    
    
    public function renew(){
    	
		$agency = new Model("Agency");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['brand_id'] = $this->_param('brand_id');
        $data['category_id'] = implode(",",$this->_param('category_id'));
        $data['city_id'] = $this->_param('city_id');
        $data['area_id'] = $this->_param('area_id');
		$data['telephone'] = $this->_param('telephone');
        $data['operating_hours'] = $this->_param('operating_hours');
        $data['main_business'] = $this->_param('main_business');
        $data['other_info'] = $this->_param('other_info');
        $data['vouchsafe'] = $this->_param('vouchsafe');
		$data['address'] = $this->_param('address');
        $data['long_lat'] = $this->_param('long_lat');

		/*$status = $this->_param('status');
		$data['status'] = $status[0];*/
		$pno = $this->_param('pno') ? $this->_param('pno') : 1;
		
		import('ORG.Net.FileManage');
		$upload_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Agency/';
		if ($_FILES['picture']['name'] ) {//展示图
			$filemanage = new FileManage($_FILES['picture'], $upload_path );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$picture = '/Public/Upload/Agency/'.date('Ym').'/'. basename($arr_img['path']);
			} else {
				$this->error($filemanage->errormsg);
			}
		}
		
		$agency->create();
		if($picture!=''){
			$data['picture'] = $picture;
		}
        $sign_type = $this->_param('sign_type');//print_r($this->_param('sign_type'));die;
        $data['sign_type'] = $sign_type[0];
        $promotion_type = $this->_param('promotion_type');
        $data['promotion_type'] = $promotion_type[0] ? $promotion_type[0] : '';
        $join_best = $this->_param('join_best');
        $data['join_best'] = $join_best[0] ? $join_best[0] : '';
		$data['discount_rate'] = $this->_param('discount_rate');
		$data['rebate_cash'] = $this->_param('rebate_cash');
		
		$agency->save($data);
		
		
        $agency_id = $data['pkid'];
		//相册上传
		if ($_FILES['album']) {
		   $file_ary = $this->reArrayFiles($_FILES['album']);//print_r($file_ary);//die;
		   $image_form = array();//print $upload_path;
		   foreach ($file_ary as $file) {
				$filemanage = new FileManage($file, $upload_path );//print $upload_path;
				if ( $filemanage->upload() ) {
					$arr_image['path'] = $filemanage->get_file_path();//print_r($arr_image['path']);
					$arr_path = '/Public/Upload/Agency/'.date('Ym').'/'.basename($arr_image['path']);
					$image_form[] = $arr_path;
				}
		   }
		   //print_r($image_form);die;
		   $arr_img_title = $this->_param('img_title');
		   
		   //写入数据库
		   $agency_picture = new Model("AgencyPicture");
		   foreach($image_form as $key=>$img){
		   	   $agency_picture->create();
		   	   $agency_picture->pkid = '';
		   	   $agency_picture->agency_id = $agency_id;
		   	   $agency_picture->title = $arr_img_title[$key];
		   	   $agency_picture->picture = $img;
		   	   $agency_picture->status = 'Y';
		   	   $agency_picture->ctime = date('Y-m-d H:i:s');
			   $agency_picture->add();
		   }
		}
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Agency/index/p/".$pno,2,$msg);
		
    }
	
	public function bindCourse(){
		$agency_id = $this->_param('agency_id');
		
		$course_id = $this->_param('course_id');
		$agency_bind_course = new Model("AgencyBindCourse");
		
		$agency = new Model("Agency");
		$data_agency = $agency->where('pkid='.$agency_id)->find();
		
		$agencyList = $agency->where("brand_id=".$data_agency['brand_id'])->select();
		foreach($agencyList as $k=>$v){
			$exist = $agency_bind_course->where("agency_id=".$v['pkid']." AND course_id=".$course_id)->find();
			if(empty($exist)){
				$data['agency_id'] = $v['pkid'];
				$data['course_id'] = $course_id;
				$data['ctime'] = date("Y-m-d H:i:s");
				$agency_bind_course->data($data)->add();;
			}
		}
		$pno = $this->_param('pno')?$this->_param('pno'):1;
        //$msg = mb_convert_encoding("OK","UTF-8","GB2312");
        //redirect("/Backend/Agency/edit/pkid/".$agency_id."/tab/2/pno/".$pno,1,$msg);
        redirect("/Backend/Agency/edit/pkid/".$agency_id."/tab/2/pno/".$pno);
        
    	/*$sql = "SELECT abc.id,c.category_id,(SELECT cat.title FROM agency_category cat WHERE cat.pkid=c.second_id) as second_title,".
    			" (SELECT cat.title FROM agency_category cat WHERE cat.pkid=c.third_id) as third_title, c.title as course_title ".
    			" FROM agency_bind_course abc LEFT JOIN agency_course c ON abc.course_id=c.pkid WHERE abc.agency_id= ".$agency_id;
    	$data_list_course = $agency_bind_course->query($sql);
		
		$this->assign('arr_category', $this->categorySet());
		$this->assign('data_list_course', $data_list_course);
		$this->display();*/
	}

    public function delimg(){

        $pkid = $this->_param('pkid');

        $agency_picture = new Model("AgencyPicture");
        $data_picture = $agency_picture->query("SELECT * FROM agency_picture WHERE pkid=".$pkid);

        $p_path = dirname(dirname(dirname(dirname(__FILE__)))).$data_picture[0]['picture'];
        @unlink($p_path);

        $sql = 'DELETE FROM agency_picture WHERE pkid='.$pkid;
        $agency_picture->query($sql);

        $msg = mb_convert_encoding("OK","UTF-8","GB2312");
        redirect("/Backend/Agency/edit/pkid/".$data_picture[0]['agency_id']."/tab/1",1,$msg);
        //redirect("/Backend/Agency/index");
    }
    
    public function delCourse(){

        $id = $this->_param('id');
		$pno = $this->_param('pno');
		
        $agency_bind_course = new Model("AgencyBindCourse");
        $data = $agency_bind_course->where("id=".$id)->find();
        $agency_id = $data['agency_id'];
        $course_id = $data['course_id'];
        
        $agency = new Model("Agency");
        $data_agency = $agency->where('pkid='.$agency_id)->find();
        $brand_id = $data_agency['brand_id'];
		//print_r($data);
		//删除
		if(!empty($brand_id)){
			$agency_list = $agency->where('brand_id='.$brand_id)->select();
			if(!empty($agency_list)){
				foreach($agency_list as $k=>$v){
					$agency_bind_course->where("agency_id=".$v['pkid']." AND course_id=".$course_id)->delete();
				}
			}
		}else{
			$agency_bind_course->where("id=".$id)->delete();
		}
		
		//print_r($data);die;
        $msg = mb_convert_encoding("OK","UTF-8","GB2312");
        redirect("/Backend/Agency/edit/pkid/".$data['agency_id']."/tab/2/pno/".$pno,1,$msg);
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency = new Model("Agency");
		
		$sql = 'DELETE FROM agency WHERE pkid='.$pkid;
		//echo $sql;die;
		$agency->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Agency/index",1,$msg);
    	//redirect("/Backend/Agency/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency = new Model("Agency");

		$agency->query("UPDATE agency SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Agency/index",1,$msg);
    	//redirect("/Backend/Agency/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency = new Model("Agency");

		$agency->query("UPDATE agency SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Agency/index",1,$msg);
    	//redirect("/Backend/Agency/index");
    }
    
    public function get_brand(){
    	$agency_brand = new Model("AgencyBrand");
		$data_list = $agency_brand->query("SELECT * FROM agency_brand");
		
		foreach($data_list as $k=>$v){
			$brand_list[$v['pkid']] = $v['title'];
		}
		return $brand_list;
    }

    public function get_category(){
        $agency_category = new Model("AgencyCategory");
        $data_list = $agency_category->query("SELECT * FROM agency_category where parent_id=1");

        foreach($data_list as $k=>$v){
            $category_list[$v['pkid']] = $v['title'];
        }
        return $category_list;
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
    private function cityList(){
        $city = D('ProvinceCity');
        $sql = "select item_id,name FROM province_city where level=2 and status=1 order by item_id asc ";
        $data_list = $city->query($sql);
        foreach($data_list as $key=>$val){
            $city_list[$val['item_id']] = $val['name'];
        }

        return $city_list;
    }

    private function areaList($city_id){
        $city = D('ProvinceCity');
        $sql = "select item_id,name FROM province_city where level=3 and status=1 and parent_id=".$city_id." order by item_id asc ";
        $data_list = $city->query($sql);
        foreach($data_list as $key=>$val){
            $area_list[$val['item_id']] = $val['name'];
        }

        return $area_list;
    }


    private function getCategoryList(){
    	
    	$category = new Model("AgencyCategory");
    	$category_list = $category->query("SELECT pkid,title FROM agency_category where pkid>0 and level=1");
    	
    	$list_cate = array();
    	foreach($category_list as $key=>$val){
    		$list_cate[] = $val['pkid'];
    	}
    	
    	return $list_cate;
    	
    }

    /*
    * 获取子分类
    */
    public function getSubCategory($parent_id){
		$agency_category = new Model("AgencyCategory");
		//进行原生的SQL查询
		if($parent_id){
			$data_list = $agency_category->query("Select * FROM agency_category WHERE parent_id=".$parent_id." ORDER BY pkid ASC ");
			return $data_list;
		}
		/*if($type=='select'){
	    	foreach($data_list as $key=>$val){
	    		$cate_list[$val['pkid']] = $val['title'];
	    	}
	    	return $cate_list;
    	}else{
    		return $data_list;
    	}*/
    }

    /*
    * 获取分类下科目
    */
    public function getCourse(){
		$agency_course = new Model("AgencyCourse");
		//进行原生的SQL查询
		$category_id = $_GET['category_id'];
		$second_id = $_GET['second_id'];
		$third_id = $_GET['third_id'];
		$sql = "Select * FROM agency_course WHERE status='Y' ";
		if($category_id!=''){
			$sql .= " AND category_id = ".$category_id;
		}
		if($second_id!=''){
			$sql .= " AND second_id = ".$second_id;
		}
		if($third_id!=''){
			$sql .= " AND third_id = ".$third_id;
		}
		$sql .= " ORDER BY title ASC ";
		$data_list = $agency_course->query($sql);
		$this->assign('course_list',$data_list);
		$this->assign('category_id',$data_list[0]['category_id']);
		
		$this->display("course_select");
    }

    function reArrayFiles(&$file_post) {

		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);
	    for ($i=0; $i<$file_count; $i++) {
	       foreach ($file_keys as $key) {
	           $file_ary[$i][$key] = $file_post[$key][$i];
	       }
	   }
		
	   return $file_ary;
	}
}
?>