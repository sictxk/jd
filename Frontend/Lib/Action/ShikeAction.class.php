<?php

class ShikeAction extends Action {
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
    public function index(){


    	
		$this->display('index');
    }
    public function index2(){


    	
		$this->display('index2');
    }
	public function search(){
		$agency = new Model("Agency");
		
		$condition['cid'] = $this->_param('cid') ? intval($this->_param('cid')): '';//一级
		$condition['sid'] = $this->_param('sid') ? intval($this->_param('sid')): '';//二级
		$condition['tid'] = $this->_param('tid') ? intval($this->_param('tid')): '';//三级
		
        $sql = "SELECT  a.pkid,a.brand_id,a.title as agency_title,a.address,ab.title,ab.logo ".
				" FROM agency a  LEFT JOIN agency_brand ab  ON a.brand_id=ab.pkid ";
		$sql_c = "SELECT count(a.pkid) as total FROM agency a LEFT JOIN agency_brand ab ON a.brand_id=ab.pkid ";
		$left_join_category = " LEFT JOIN agency_bind_course abc ON abc.agency_id = a.pkid  ".
                                " LEFT JOIN agency_course ac ON ac.pkid = abc.course_id ";
		$sql.=$left_join_category;
		$sql_c.=$left_join_category;
		
		$where = " WHERE a.pkid>0 and ab.status='Y'";

		if($condition['cid']!=''){
			$where.=" AND ac.category_id=".$condition['cid'];
		}
		if($condition['sid']!=''){
			$where.=" AND ac.second_id=".$condition['sid'];
		}
		if($condition['tid']!=''){
			$where.=" AND ac.third_id=".$condition['tid'];
		}
		$count_sql = $sql_c.$where;;

		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 12;
			
		$list_sql = $sql.$where." ORDER BY ab.pkid desc limit ".($cur_page-1)*$page_size.",".$page_size;//echo $list_sql;//die;
		$agency_list = $agency->query($list_sql);
		
		$this->assign('agency_list',$agency_list);
		
		import("ORG.Util.Page");
		
		$count_num = $agency->query($count_sql);
		$count = $count_num[0]['total'];
		
		$Page = new Page($count,$page_size);
		
		$show       = $Page->showFront();//echo $show;
		$this->assign('page',$show);
		$this->assign('pno',$cur_page);

		$this->assign('area_list',$this->getAreaSelect(3101));//print_r($this->getAreaSelect(3101));
		$this->assign('s_category', $this->getCategorySelect());
		$this->assign('condition',$condition);
		
		$this->display('search');
	}
	
	public function location(){
		
		
		$this->display('location');
	}
    
    private function getAreaSelect($city_id){

        $list = array();
        if($city_id!=''){
            $ProvinceCity = D('ProvinceCity');
            $list = $ProvinceCity->where("parent_id=".$city_id." AND status=1")->select();
            foreach($list as $key=>$val){
                $city_list[$val['item_id']] = $val['name'];
            }
        }
        //return $list;
        return $city_list;
    }
    
    private function getCategorySelect(){

        $Category = D('AgencyCategory');
        $list = $Category->query("SELECT * FROM agency_category WHERE pkid>0 AND level=1");
        foreach($list as $k=>$v){
              $data[$v['pkid']] = $v['title'];
        }
        //return $list;
        return $data;
    }
    
    private function getBrandSelect(){

        $AgencyBrand = D('AgencyBrand');
        $list = $AgencyBrand->select();
        foreach($list as $k=>$v){
              $data[$v['pkid']] = $v['title'];
        }///print_r($data);
        //return $list;
        return $data;
    }
    
    
    public function map(){
    	
    	$this->display('map');
    }
    
    public function lbs(){
    	
    	$this->display('lbs');
    }
}