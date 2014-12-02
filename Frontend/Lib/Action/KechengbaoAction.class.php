<?php

class KechengbaoAction extends Action {
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
	/**
	* 机构首页-品牌列表搜索展示
	*/
    public function index(){
		
		$kechengbao = new Model("Kechengbao");
		
		$condition['area'] = $this->_param('area') ? intval($this->_param('area')): '';
		$condition['price'] = $this->_param('price')>=0 ? $this->_param('price'): '';
		$condition['begin_type'] = $this->_param('begin_type') ? $this->_param('begin_type'): '';
		
		$arr_price = array(
			1=>array('price'=>'1-999','title'=>'￥1000以下'),
			2=>array('price'=>'1000-1999','title'=>'￥1000-1999'),
			3=>array('price'=>'2000-2999','title'=>'￥2000-2999'),
			4=>array('price'=>'3000-3999','title'=>'￥3000-3999'),
			5=>array('price'=>'4000-4999','title'=>'￥4000-4999'),
			6=>array('price'=>'5000-99999','title'=>'￥5000以上')
		);
		
        $sql = "SELECT  ab.pkid as kecheng_id,ab.title,ab.image,ab.market_price,ab.yitu_price ".
				" FROM kechengbao ab  ";
		$sql_c = "SELECT count(distinct ab.pkid) as total FROM kechengbao ab  ";
		$where = " WHERE ab.pkid>0 and ab.status='Y'";
		if($condition['area']!=''){
			$left_join_agency_area = " LEFT JOIN kecheng_agency ka ON ka.kecheng_id = ab.pkid LEFT JOIN agency a ON a.pkid=ka.agency_id ";
			$sql.=$left_join_agency_area;
			$sql_c.=$left_join_agency_area;
			$where.=" AND a.area_id=".$condition['area'];
		}
		if($condition['price']!='' && is_numeric($condition['price'])){
			$price_range = explode('-',$arr_price[$condition['price']]['price']);
			$price_min = $price_range[0];
			$price_max = $price_range[1];
			$where.=" AND ab.yitu_price>=".$price_min." AND ab.yitu_price<=".$price_max;
		}
		if($condition['begin_type']!=''){
			$where.=" AND ab.begin_type=".$condition['begin_type'];
		}
		$count_sql = $sql_c.$where;//echo $count_sql;
		$where.=" GROUP BY ab.pkid ";

		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 18;
			
		$list_sql = $sql.$where." ORDER BY ab.pkid desc limit ".($cur_page-1)*$page_size.",".$page_size;//echo $list_sql;//die;
		$kechengbao_list = $kechengbao->query($list_sql);
		$this->assign('kechengbao_list',$kechengbao_list);
		
		import("ORG.Util.Page");
		
	
		$count_num = $kechengbao->query($count_sql);
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
		
		$this->assign('condition',$condition);

        $url_unit = array('area','price','begin_type');
        $url_base = '/Member/Kechengbao/index';
        $arr_url = array();
        
        foreach($url_unit as $val){
        	$condition2 = $condition;
            $arr_url[$val] = $url_base;
            foreach($condition2 as $k=>$v){
                if($val!=$k  && $v!=''){
                    $arr_url[$val] .= "/".$k."/".$v;
                }
            }
        }
        $this->assign('arr_url',$arr_url);
        
		$this->assign('arr_price',$arr_price);
		
		$this->assign('arr_begin_type',array(1=>'七天内开班',2=>'十五天内开班',3=>'约定开班时间'));
		
		$this->display();
		
    }
    
    public function show(){
		$kechengbao = new Model("Kechengbao");
		
        $kecheng_id = $this->_param('id') ? intval($this->_param('id')) : $agencyId ;
        if(!is_numeric($kecheng_id)){
            exit;
        }
        //get agency data
        
        $sql = "SELECT kc.*,ab.title as brand_title,ab.logo,ab.intro FROM kechengbao kc LEFT JOIN agency_brand ab ON kc.brand_id=ab.pkid WHERE kc.pkid=".$kecheng_id;
        $data_kecheng = $kechengbao->query($sql);
        if($data_kecheng[0]['image']){
            $icon_avatar = str_replace('.jpg',"_198.jpg",$data_kecheng[0]['image']);
            $root_path = dirname(dirname(dirname(dirname(__FILE__))));
            if(!file_exists($root_path.$icon_avatar)){
                //图片裁切为指定尺寸
                import('ORG.Util.Image');
                $img = new Image;
                $img->thumb($root_path.$data_kecheng[0]['image'],$root_path.$icon_avatar,'',198, 206,true);
            }
            $data_kecheng[0]['thumb'] = $icon_avatar;
        }
        $data_kecheng[0]['lesson_content'] = html_entity_decode($data_kecheng[0]['lesson_content'],ENT_COMPAT,"UTF-8");
        $this->assign('data_kecheng',$data_kecheng[0]);
		
		$agency_idList = $data_kecheng[0]['agency_id'];
		
        //get agency 
        $agency = new Model("Agency");
        $data_agency = $agency->where("pkid in(".$agency_idList.")")->select();
        $this->assign('data_agency',$data_agency);
		
        //get agency picture
        $agency_picture = new Model("AgencyPicture");
        $data_agency_picture = $agency_picture->where("agency_id in(".$agency_idList.")")->limit(0,6)->select();
        foreach($data_agency_picture as $key=>$val){
            if($val['picture']!=''){
                $img_thumb = str_replace(".jpg","_280.jpg",$val['picture']);
                $root_path = dirname(dirname(dirname(dirname(__FILE__))));
                if(!file_exists($root_path.$img_thumb)){
                    //图片裁切为指定尺寸
                    import('ORG.Util.Image');
                    $img = new Image;
                    $img->thumb($root_path.$val['picture'],$root_path.$img_thumb,'',280, 187,true);
                }
                $data_agency_picture[$key]['picture_280'] = $img_thumb;
            }
        }
        $this->assign('data_agency_picture',$data_agency_picture);
		
        //get other kecheng in same brand 
        $other_kecheng = $kechengbao->where("brand_id =".$data_kecheng[0]['brand_id'])->limit(0,10)->select();
        $this->assign('other_kecheng',$other_kecheng);
        
		//get comment list
        $comment_list = $this->getComment($kecheng_id);
        $this->assign('comment_list',$comment_list);
        $this->assign('comment_num',count($comment_list));
		
		
        $url_base = '/Member/Agency/index';
        $this->assign('url_base', $url_base);

		$this->assign('user_id',$_SESSION['user_info']['pkid']);

		$this->display();
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

        $kechengbao_course = D('AgencyCourse');
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

	        $list = $kechengbao_course->where($condition)->order("pkid asc")->select();
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

	private function getComment($kecheng_id){
		$KechengComment = D('KechengComment');
		$sql = "SELECT ac.comment,ac.score+1 as score,ac.ctime,u.nickname,u.avatar,ac.user_id,ac.bespeaked FROM agency_comment ac ".
				" LEFT JOIN user u ON ac.user_id=u.pkid ".
				" WHERE ac.agency_id=".$kecheng_id." order by ac.ctime desc";//echo $sql;die;
        $list = $KechengComment->query($sql);
        return $list;
	}
    
}