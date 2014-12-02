<?php

class BrandAction extends Action {
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
	/**
	* 机构首页-品牌列表搜索展示
	*/
    public function index(){
		
		$agency = new Model("Agency");
		
		//$condition['st'] = $this->_param('st') ? intval($this->_param('st')): '';
		$condition['cid'] = $this->_param('cid') ? intval($this->_param('cid')): '';//一级
		$condition['sid'] = $this->_param('sid') ? intval($this->_param('sid')): '';//二级
		$condition['tid'] = $this->_param('tid') ? intval($this->_param('tid')): '';//三级
		$condition['course_id'] = $this->_param('course_id') ? intval($this->_param('course_id')): '';//科目
		$arr_cat = array(2,3,5,9);
		if(!in_array($condition['cid'],$arr_cat) && !empty($condition['cid'])){
			$condition['cid'] = 2 ;
		}
		$condition['area'] = $this->_param('area') ? intval($this->_param('area')): '';
		$condition['tabType'] = $this->_param('tabType') ? $this->_param('tabType'): '';
		$condition['course_title'] = $this->_param('course_title') ? trim($this->_param('course_title')): '';
		$condition['agency_title'] = $this->_param('agency_title') ? trim($this->_param('agency_title')): '';
		$condition['keyword'] = $this->_param('keyword') ? trim($this->_param('keyword')): '';

        $sql = "SELECT  ab.pkid as brand_id,ab.title,ab.logo ".
				" FROM agency_brand ab  LEFT JOIN agency a ON a.brand_id=ab.pkid ";
		$sql_c = "SELECT count(distinct ab.pkid) as total FROM agency_brand ab LEFT JOIN agency a ON a.brand_id=ab.pkid ";
		$left_join_category = "  LEFT JOIN agency_bind_course abc ON abc.agency_id = a.pkid  ".
                                "LEFT JOIN agency_course ac ON ac.pkid = abc.course_id ".
                                " LEFT JOIN agency_category cat ON ac.category_id = cat.pkid  ";
		$sql.=$left_join_category;
		$sql_c.=$left_join_category;
		
		//$where = " WHERE ab.pkid>0 AND a.picture<>'' ";
		$where = " WHERE ab.pkid>0 and ab.status='Y'";
		if($condition['cid']!=''){
			$where.=" AND ac.category_id=".$condition['cid'];
		}
		if($condition['sid']!=''){
			$where.=" AND ac.second_id=".$condition['sid'];
		}
		if($condition['tid']!=''){
			$where.=" AND ac.third_id=".$condition['tid'];
		}
		if($condition['course_id']!=''){
			$where.=" AND abc.course_id=".$condition['course_id'];
		}
		if($condition['area']!=''){
			$left_join_agency_area = "LEFT JOIN province_city pc ON a.area_id=pc.pkid ";
			$sql.=$left_join_agency_area;
			$sql_c.=$left_join_agency_area;
			$where.=" AND a.area_id=".$condition['area'];
		}
		if($condition['course_title']!=''){
			$where.=" AND (ac.title like '%".$condition['course_title']."%' OR cat.title like '%".$condition['course_title']."%')";
		}
		if($condition['agency_title']!=''){
			$where.=" AND (a.title like '%".$condition['agency_title']."%' OR ab.title like '%".$condition['agency_title']."%')";
		}
		if($condition['keyword']!=''){
			$where.=" AND (a.title like '%".$condition['keyword']."%' OR ac.title like '%".$condition['keyword']."%' OR a.address like '%".$condition['keyword']."%')";
		}
		$count_sql = $sql_c.$where;//echo $count_sql;
		$where.=" GROUP BY ab.pkid ";

		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 16;
			
		$list_sql = $sql.$where." ORDER BY ab.pkid desc limit ".($cur_page-1)*$page_size.",".$page_size;//echo $list_sql;//die;
		$agency_list = $agency->query($list_sql);
		$this->assign('agency_list',$agency_list);
		
		import("ORG.Util.Page");
		
	
		$count_num = $agency->query($count_sql);
		$count = $count_num[0]['total'];
		
		$Page = new Page($count,$page_size);
		if($condition){
			foreach($condition as $key=>$val) {
				if($val!=''){
			    	$Page->parameter   .=   "$key=".urlencode($val).'&';
			    }
			}
		}
		$show       = $Page->showFront();//echo $show;
		$this->assign('page',$show);
		$this->assign('pno',$cur_page);

		$this->assign('area_list',$this->getAreaSelect(3101));//print_r($this->getAreaSelect(3101));
		$this->assign('s_category', $this->getCategorySelect());//print_r($this->getCategorySelect());
		if(!empty($condition['cid'])){$this->assign('s_course', $this->getCourseSelect($condition['cid'],$condition['sid'],$condition['tid']));}
		if(!empty($condition['cid'])){$this->assign('sub_category', $this->getSubCategory($condition['cid']));}
		if(!empty($condition['sid'])){$this->assign('third_category', $this->getSubCategory($condition['sid']));}
		$this->assign('condition',$condition);

        $url_unit = array('area','cid','sid','tid','course_id','course_title','agency_title','keyword');
        $url_base = '/Member/Brand/index';
        $arr_url = array();
        
        foreach($url_unit as $val){
        	$condition2 = $condition;
            $arr_url[$val] = $url_base;
            if($val=='cid'){
            	unset($condition2['sid']);
            	unset($condition2['tid']);
            }
            if($val=='sid'){
            	unset($condition2['tid']);
            }
            foreach($condition2 as $k=>$v){
                if($val!=$k  && $v!=''){
                    $arr_url[$val] .= "/".$k."/".$v;
                }
            }
        }
        $this->assign('arr_url',$arr_url);
		$brand_url = '/Member/Agency/blist';
		$arr = array('area','category','course');
		foreach($arr as $k){
			if($condition[$k]!=''){
				$brand_url.= "/".$k."/".$condition[$k];
			}
		}
		
		$this->assign('brand_url',$brand_url);
		
		
		
		if(empty($agency_list)){
			if($condition['tabType']=='agency'){
				$this->display('NoAgency');
			}elseif($condition['tabType']=='course'){
				$this->display('NoCourse');
			}else{
				$this->display();
			}
		}else{
			$this->display();
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
	private function cityList(){
		$city = D('ProvinceCity');
		$sql = "select item_id,name FROM province_city where level=2 and status=1 order by item_id asc ";
		$data_list = $city->query($sql);
		/*foreach($data_list as $key=>$val){
			$city_list[$val['item_id']] = $val['name'];
		}*/
		
		return $data_list;
    }
    
    
	private function getCategory(){
		
		$Category = D('AgencyCategory');
		$list = $Category->select();	
		/*foreach($list as $k=>$v){
			$data[$k]['pkid'] = $v['title'];
		}*/
		return $list;
    }

    private function getCategorySelect(){

        $Category = D('AgencyCategory');
        $list = $Category->query("SELECT * FROM agency_category WHERE pkid>0 AND level=1");
        /*foreach($list as $k=>$v){
              $data[$v['pkid']] = $v['title'];
        }*/
        return $list;
        //return $data;
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
    private function getCourseSelect($cid,$sid='',$tid=''){

        $agency_course = D('AgencyCourse');
        if($tid==''){
        	return '';
        }else{
	        if($cid!=''){
	        	$condition['category_id'] = $cid;
	        }
	        if($sid!=''){
	        	$condition['second_id'] = $sid;
	        }
	        if($tid!=''){
	        	$condition['third_id'] = $tid;
	        }
			
	      	$condition['status'] = 'Y';

	        $list = $agency_course->where($condition)->order("pkid asc")->select();
	        return $list;
        }
    }
	
	
    private function getSubCategory($cat_id){

        $category = D('AgencyCategory');
        $list = $category->where("parent_id=".$cat_id." AND status='Y'")->order("pkid asc")->select();
        /*foreach($list as $k=>$v){
              $data[$v['pkid']] = $v['title'];
          }*/
        return $list;
    }

    
    
}