<?php

class CommonAction extends Action {

	public function getarea_select(){
		
		$city_id = $_GET['city_id'];
		
		$province_city = new Model("ProvinceCity");
		$data_list = $province_city->query("Select item_id,name FROM province_city WHERE parent_id=".$city_id." AND status=1 ORDER BY item_id ASC ");

    	/*$area_list = array();
    	foreach($data_list as $key=>$val){
			$area_list[$val['item_id']] = $val['name'];
    	}

		$this->assign('area_list',$area_list);*/
		
		$this->assign('area_list',$data_list);
		$this->display('area_select');
	}
	
	public function getarea_checkbox(){
		
		$city_id = $_GET['city_id'];
		
		$province_city = new Model("ProvinceCity");
		$data_list = $province_city->query("Select item_id,name FROM province_city WHERE parent_id=".$city_id." AND status=1 ORDER BY item_id ASC ");
		
    	/*$area_list = array();
    	foreach($data_list as $key=>$val){
			$area_list[$val['item_id']] = $val['name'];
    	}
		
		$this->assign('area_list',$area_list);*/
		$this->assign('area_list',$data_list);
		$this->display('area_checkbox');
	}
	
	public function getcourse_select(){
		
		$category_id = $_GET['category_id'];
		
		$course = new Model("Course");
		$data_list = $course->query("Select course_id,title FROM course WHERE category_id=".$category_id." AND status='Y' ORDER BY course_id ASC ");
		
    	$course_list = array();
    	foreach($data_list as $key=>$val){
			$course_list[$val['course_id']] = $val['title'];
    	}
		
		$this->assign('course_list',$course_list);
		$this->display('course_select');
	}
	
}