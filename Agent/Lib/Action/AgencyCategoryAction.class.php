<?php

class AgencyCategoryAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 and parent_id=1 ';

		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');

		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }
		
		$map_sql .= " AND level=1";

		
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 10;
		
		$agency_category = new Model("AgencyCategory");
		
		if($map){
			$list = $agency_category->where($map_sql)->order('pkid asc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $agency_category->order('pkid asc')->page($cur_page.",".$page_size)->select();
		}
		$data_list = array();
		$agency_category = new Model("AgencyCategory");
		foreach($list as $key=>$val){
			$data_list[] = $val;
			$sub_list = array();
			$sub_list = $agency_category->where("parent_id=".$val['pkid'])->select();
			foreach($sub_list as $k=>$v){
				$data_list[] = $v;
				$third_list = $agency_category->where("parent_id=".$v['pkid'])->select();
				foreach($third_list as $kk=>$vv){
					$data_list[] = $vv;
				}
			}
		}
		$this->assign('category_list',$data_list);
		import("ORG.Util.Page");
		if($map){
			$count  = $agency_category->where($map_sql)->count();
		}else{
			$count  = $agency_category->count();
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
    	
    	
    	$arr_parent_agency_category = $this->getParentCategory($_GET['parent_id']);
    	
    	$this->assign('parent_id', $arr_parent_agency_category);
    	
    	$this->assign('parent_value', $_GET['parent_id']);
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$agency_category = new Model("AgencyCategory");
    	$arr_form = $agency_category->query('SELECT * FROM agency_category WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	$this->assign('value', $arr_form[0]['status']);
    	$this->assign('parent_id', $arr_form[0]['parent_id']);
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));

    	$arr_parent_agency_category = $this->getParentCategory($arr_form[0]['parent_id']);
    	
    	$this->assign('parent_ids', $arr_parent_agency_category);
    	
    	
		$this->display();
    }
    
    public function save(){
    	
		$agency_category = new Model("AgencyCategory");
		$parent_id = $this->_param('parent_id');
		$data_parent = $agency_category->query("SELECT level+1 as level FROM agency_category WHERE pkid=".$parent_id);
		
		$agency_category->create();
		$agency_category->level = $data_parent[0]['level'];
		$agency_category->parent_id = $parent_id;
		$agency_category->add();
		
    	redirect("/Backend/AgencyCategory/index");
    }
    
    
    public function renew(){
    	
		$agency_category = new Model("AgencyCategory");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['parent_id'] = $this->_param('parent_id');	
		
		
		$data_parent = $agency_category->query("SELECT level+1 as level FROM agency_category WHERE pkid=".$data['parent_id']);
		$data['level'] = $data_parent[0]['level'];
		
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$agency_category->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCategory/index",1,$msg);
    	//redirect("/Backend/AgencyCategory/index");
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency_category = new Model("AgencyCategory");
		//进行原生的SQL查询
		$agency_category->query('DELETE FROM agency_category WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCategory/index",1,$msg);
    	//redirect("/Backend/AgencyCategory/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency_category = new Model("AgencyCategory");
		//进行原生的SQL查询
		$agency_category->query("UPDATE agency_category SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCategory/index",1,$msg);
    	//redirect("/Backend/AgencyCategory/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency_category = new Model("AgencyCategory");
		//进行原生的SQL查询
		$agency_category->query("UPDATE agency_category SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyCategory/index",1,$msg);
    	//redirect("/Backend/AgencyCategory/index");
    }
    
    public function getParentCategory($parent_id){
		$agency_category = new Model("AgencyCategory");
		$data_parent = $agency_category->where('pkid = '.$parent_id)->find();
		//进行原生的SQL查询
		$data_list = $agency_category->query("Select * FROM agency_category WHERE level=".$data_parent['level']." AND parent_id=".$data_parent['parent_id']." ORDER BY pkid ASC ");
    	
    	foreach($data_list as $key=>$val){
    		$cate_list[$val['pkid']] = $val['title'];
    	}
    	return $cate_list;
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
    
	public function getSecond(){
		
		$category_id = $_GET['category_id'];
		
		$category_list = $this->getSubCategory($category_id);
		
		$this->assign('category_list',$category_list);
		
		if($category_list!=''){
			$this->display('second_select');
		}
	}
    
	public function getThird(){
		
		$category_id = $_GET['second_id'];
		
		$category_list = $this->getSubCategory($category_id);
		
		$this->assign('category_list',$category_list);
		
		if($category_list){
			$this->display('third_select');
		}
	}
	
	public function getCourse(){
		
		$third_id = $_GET['third_id'];
		$agency_course = new Model("AgencyCourse");
		$data_list = $agency_course->where("third_id = ".$third_id)->select();
		$course_list = array();
		if($data_list){
			foreach($data_list as $k=>$v){
				$course_list[$v['pkid']] = $v['title'];
			}
		}
		$this->assign('course_list',$course_list);
		$this->display('course_select');
		
	}
}
?>