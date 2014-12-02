<?php
header("Content-type: text/html;charset=GB2312");
class AgencyAction extends Action {
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
	/**
	* 机构首页-品牌列表搜索展示
	*/
    public function index(){
		
		$agency = new Model("Agency");
		
		$condition['st'] = $this->_param('st') ? intval($this->_param('st')): '';
		$condition['category'] = $this->_param('category') ? intval($this->_param('category')): '';
		/*$arr_cat = array(2,3,4,5);
		if(!in_array($condition['category'],$arr_cat)){
			$condition['category'] = 2 ;
		}*/
		//$condition['city'] = $this->_param('city') ? $this->_param('city'): '';
		$condition['area'] = $this->_param('area') ? intval($this->_param('area')): '';
		$condition['course'] = $this->_param('course') ? intval($this->_param('course')): '';
        //$condition['brand'] = $this->_param('brand') ? intval($this->_param('brand')): '';
		$condition['keyword'] = $this->_param('keyword') ? trim($this->_param('keyword')): '';

        $sql = "SELECT  ab.pkid as brand_id,ab.title,ab.picture,a.thumb,ab.logo ".
				" FROM agency_brand ab  LEFT JOIN agency a ON a.brand_id=ab.pkid ";
		$sql_c = "SELECT count(distinct ab.pkid) as total FROM agency_brand ab LEFT JOIN agency a ON a.brand_id=ab.pkid ";
		$left_join_category = "  LEFT JOIN agency_bind_course abc ON abc.agency_id = a.pkid  ".
                                "LEFT JOIN agency_course ac ON ac.pkid = abc.course_id ".
                                " LEFT JOIN agency_category cat ON ac.category_id = cat.pkid  ";
		$sql.=$left_join_category;
		$sql_c.=$left_join_category;
		
		//$where = " WHERE ab.pkid>0 AND a.picture<>'' ";
		$where = " WHERE ab.pkid>0 and ab.status='Y'";
		if($condition['st']!=''){
			$where.=" AND a.sign_type=".$condition['st'];
			$condition['nav'] = 'tehui';
		}else{
			$condition['nav'] = 'peixun';
		}
		if($condition['category']!=''){
			$where.=" AND ac.category_id=".$condition['category'];
            if($condition['course']!='' ){
                $where.=" AND ac.pkid=".$condition['course'];
            }
		}
		if($condition['area']!=''){
			$left_join_agency_area = "LEFT JOIN province_city pc ON a.area_id=pc.pkid ";
			$sql.=$left_join_agency_area;
			$sql_c.=$left_join_agency_area;
			$where.=" AND a.area_id=".$condition['area'];
		}
        /*if($condition['brand']!=''){
            $where.=" AND ab.pkid=".$condition['brand'];
        }*/
		if($condition['keyword']!=''){
			$where.=" AND (a.title like '%".$condition['keyword']."%' OR ac.title like '%".$condition['keyword']."%' OR a.address like '%".$condition['keyword']."%')";
		}
		$count_sql = $sql_c.$where;//echo $count_sql;
		$where.=" GROUP BY ab.pkid ";

		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 16;
			
		$list_sql = $sql.$where." ORDER BY ab.pkid desc limit ".($cur_page-1)*$page_size.",".$page_size;//echo $list_sql;//die;
		$agency_list = $agency->query($list_sql);
        /*foreach($agency_list as $key=>$val){
            if($val['picture']!=''){
                $img_thumb = str_replace("/Public/Upload/Agency/","/Public/Upload/AgencyThumb/",$val['picture']);
                $root_path = dirname(dirname(dirname(dirname(__FILE__))));
                if(!file_exists($root_path.$img_thumb)){
                    //图片裁切为指定尺寸
                    import('ORG.Util.Image');
                    $img = new Image;
                    $img->thumb($root_path.$val['picture'],$root_path.$img_thumb,'',200, 200,true);
                }
                //$data_list_log[$key]['avatar'] = $icon_avatar;
            }
        }*/
        //print_r($agency_list);
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
		if(!empty($condition['category'])){$this->assign('s_course', $this->getCourseSelect($condition['category']));}
		$this->assign('s_brand_list', $this->getBrandSelect());
		$this->assign('condition',$condition);

        $url_unit = array('area','category','course','brand','keyword');
        $url_base = '/Member/Agency/index';
        $arr_url = array();
        foreach($url_unit as $val){
            $arr_url[$val] = $url_base;
            foreach($condition as $k=>$v){
                if($val!=$k  && $v!=''){
                    $arr_url[$val] .= "/".$k."/".$v;
                }
            }
        }
        $this->assign('arr_url',$arr_url);
		//print_r($arr_url);
		//$brand_url = '/Member/Agency/blist/area/'.$condition['area'].'/category/'.$condition['category'].'/course/'.$condition['course'];
		$brand_url = '/Member/Agency/blist';
		$arr = array('area','category','course');
		foreach($arr as $k){
			if($condition[$k]!=''){
				$brand_url.= "/".$k."/".$condition[$k];
			}
		}
		
		$this->assign('brand_url',$brand_url);
		
		$this->display();
    }

	/**
	* 品牌下机构列表显示
	*/
    public function blist(){
		
		$agency = new Model("Agency");
        $condition['brand'] = $this->_param('id') ? intval($this->_param('id')): '';
        $condition['area'] = $this->_param('area') ? intval($this->_param('area')): '';
        $condition['category'] = $this->_param('category') ? intval($this->_param('category')): '';
        $condition['course'] = $this->_param('course') ? intval($this->_param('course')): '';
        $condition['nav'] = 'peixun';
        
		if(empty($condition['brand'])){
            exit;
        }
        $sql = "SELECT  distinct a.*,pc.name as area_name,(SELECT COUNT(comment.pkid) FROM agency_comment comment WHERE comment.agency_id=a.pkid) as comment_num FROM agency a LEFT JOIN province_city pc ON a.area_id=pc.item_id";
		$sql_c = "SELECT count( distinct a.pkid) as total FROM agency a ";
		$left_join_category = "  LEFT JOIN agency_bind_course abc ON abc.agency_id = a.pkid  ".
                                "LEFT JOIN agency_course ac ON ac.pkid = abc.course_id ".
                                " LEFT JOIN agency_category cat ON ac.category_id = cat.pkid  ";
		
		$sql.=$left_join_category;
		$sql_c.=$left_join_category;
		
		$where = " WHERE a.status='Y'  AND a.brand_id=".$condition['brand'];
		if($condition['area']){
			$where .= " AND a.area_id=".$condition['area'];
		}
		if($condition['category']!=''){
			$where.=" AND ac.category_id=".$condition['category'];
            if($condition['course']!='' ){
                $where.=" AND ac.pkid=".$condition['course'];
            }
		}
		if($condition['course']){
			$where .= " AND abc.course_id=".$condition['course'];
		}
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 10;
		
		$list_sql = $sql.$where." ORDER BY a.pkid desc limit ".($cur_page-1)*$page_size.",".$page_size;//echo $list_sql;//die;
		$agency_list = $agency->query($list_sql);
        foreach($agency_list as $key=>$val){
            if($val['picture']!=''){
                $img_thumb = str_replace("/Public/Upload/Agency/","/Public/Upload/AgencyThumb/156/",$val['picture']);
                $root_path = dirname(dirname(dirname(dirname(__FILE__))));
                if(!file_exists($root_path.$img_thumb)){
                    //图片裁切为指定尺寸
                    import('ORG.Util.Image');
                    $img = new Image;
                    $img->thumb($root_path.$val['picture'],$root_path.$img_thumb,'',156, 124,true);
                }
                $agency_list[$key]['thumb156'] = $img_thumb;
            }
        }
        //print_r($agency_list);
		$this->assign('agency_list',$agency_list);
		
		import("ORG.Util.Page");
		$count_sql = $sql_c.$where;
		$count_num = $agency->query($count_sql);
		$count = $count_num[0]['total'];
		
		$totalPage = ceil($count/$page_size);
		
		$Page = new Page($count,$page_size);
		
		$show       = $Page->showFront();//echo $show;
		$this->assign('page',$show);
		$this->assign('pno',$cur_page);
		$this->assign('count',$count);
		$this->assign('totalPage',$totalPage);
		
		$this->assign('area_list',$this->getAreaSelect(3101));
		$this->assign('s_category', $this->getCategorySelect());
		if(!empty($condition['category'])){
			$this->assign('s_course', $this->getCourseSelect($condition['category']));
		}
		$this->assign('condition',$condition);
		
        $url_unit = array('area','category','course');
        $cur_url = $url_base = '/Member/Agency/blist/id/'.$condition['brand'];
        $arr_url = array();
        foreach($url_unit as $val){
            $arr_url[$val] = $url_base;
            foreach($condition as $k=>$v){
            	if($k!='brand'){
	                if($val!=$k  && $v!=''){
	                    $arr_url[$val] .= "/".$k."/".$v;
	                }
                }
            }
            if($condition[$val]!=''){
            	$cur_url .= '/'.$val.'/'.$condition[$val];
            }
        }
        
        $this->assign('arr_url',$arr_url);
		$this->assign('cur_url',$cur_url);
		
		$agency_brand = new Model("AgencyBrand");
		$dataBrand = $agency_brand->where("pkid=".$condition['brand'])->find();
		$this->assign('dataBrand',$dataBrand);
		
		
		$this->assign('area_list',$this->getAreaSelect(3101));
		//Bread Path
		/*$BreadPath = "<a href='/Member/Agency/index/'>机构品牌</a>";
		if(!empty($condition['brand'])){
			$ArrBread['brand'] = D("AgencyBrand")->where('pkid='.$condition['brand'])->find();
			$BreadPath .= "&gt;&gt;<a href=".$url_base."/brand/".$condition['brand'].">".$ArrBread['brand']['title']."</a>";
		}
		if(!empty($condition['area'])){
			$ArrBread['area'] = D("ProvinceCity")->where('item_id='.$condition['area'])->find();
			$BreadPath .= "&gt;&gt;<a href=".$url_base."/area/".$condition['area'].">".$ArrBread['area']['name']."</a>";
		}
		if(!empty($condition['category'])){
			$ArrBread['category'] = D("AgencyCategory")->where('pkid='.$condition['category'])->find();
			$BreadPath .= "&gt;&gt;<a href=".$url_base."/category/".$condition['category'].">".$ArrBread['category']['title']."</a>";
		}
		if(!empty($condition['course'])){
			$ArrBread['course'] = D("AgencyCourse")->where('pkid='.$condition['course'])->find();
			$BreadPath .= "&gt;&gt;<a href=".$url_base."/course/".$condition['course'].">".$ArrBread['course']['title']."</a>";
		}
		$this->assign('BreadPath',$BreadPath);
		*/

		$this->display();
		
    }

    public function show(){
		$agency = new Model("Agency");
		
		$code = $this->_param('code') ? $this->_param('code') : '';
		if($code){
			$data = $agency->field('pkid')->where("code='".$code."'")->find();
			$agencyId = $data['pkid'];
		}
		
        $agency_id = $this->_param('id') ? intval($this->_param('id')) : $agencyId ;
        if(!is_numeric($agency_id)){
            exit;
        }
        //get agency data
        
        $sql = "SELECT ag.*,ab.title as brand_title,ab.logo as brand_logo,pc.name as area_name FROM agency ag LEFT JOIN agency_brand ab ON ag.brand_id=ab.pkid ".
                " LEFT JOIN province_city pc ON ag.area_id=pc.item_id WHERE ag.pkid=".$agency_id;
        $data_agency = $agency->query($sql);
        /*if($data_agency[0]['thumb']){
            $icon_avatar = str_replace('.jpg',"_220.jpg",$data_agency[0]['picture']);
            $root_path = dirname(dirname(dirname(dirname(__FILE__))));
            if(!file_exists($root_path.$icon_avatar)){
                //图片裁切为指定尺寸
                import('ORG.Util.Image');
                $img = new Image;
                $img->thumb($root_path.$data_agency[0]['picture'],$root_path.$icon_avatar,'',220, 220,true);
            }
            $data_agency[0]['thumb'] = $icon_avatar;
            $agency->query("UPDATE agency SET thumb='".$icon_avatar."' WHERE pkid=".$agency_id);
        }*/
        $data_agency[0]['point'] = str_replace(",","|",$data_agency[0]['long_lat']);
        $this->assign('data_agency',$data_agency[0]);

        //get agency picture
        if($data_agency[0]['sign_type']==1){
	        $agency_picture = new Model("AgencyPicture");
	        $data_agency_picture = $agency_picture->where("agency_id=".$agency_id)->select();
	        foreach($data_agency_picture as $k=>$v){
	            if($v['thumb']==''){
	                $icon_avatar = str_replace('.jpg',"_282.jpg",$v['picture']);
	                $root_path = dirname(dirname(dirname(dirname(__FILE__))));
	                if(!file_exists($root_path.$icon_avatar)){
	                    //图片裁切为指定尺寸
	                    import('ORG.Util.Image');
	                    $img = new Image;
	                    $img->thumb($root_path.$v['picture'],$root_path.$icon_avatar,'',282, 160,true);
	                }
	                $data_agency_picture[$k]['thumb'] = $icon_avatar;
	                $agency_picture->query("UPDATE agency_picture SET thumb='".$icon_avatar."' WHERE pkid=".$v['pkid']);
	            }
	        }
	        $this->assign('data_agency_picture',$data_agency_picture);
	        //$this->assign('agency_picture_num',count($data_agency_picture));
	        //print_r($data_agency_picture);
		}
		
        //get agency course
        $agency_bind_course = new Model("AgencyBindCourse");
        $sql = "SELECT abc.course_id,ac.title as course_title FROM agency_bind_course abc ".
                "LEFT JOIN agency_course ac ON abc.course_id=ac.pkid ".
                //"LEFT JOIN agency_category cat ON ac.category_id=cat.pkid ".
        		"WHERE abc.agency_id=".$agency_id;

        $data_course = $agency_bind_course->query($sql);
        $this->assign('data_course',$data_course);

        $this->assign('area_list',$this->getAreaSelect(3101));
        //$this->assign('s_category', $this->getCategorySelect());
        //$this->assign('s_course', $this->getCourseSelect($data_course[0]['category_id']));

        //$this->assign('s_brand_list', $this->getBrandSelect());
        $comment_list = $this->getComment($agency_id);
        $this->assign('comment_list',$comment_list);
        $this->assign('comment_num',count($comment_list));
		
		
        $url_base = '/Member/Agency/index';
        $this->assign('url_base', $url_base);

		$this->assign('user_id',$_SESSION['user_info']['pkid']);

		$this->display();
	}
	
	/*
	* 点赞/踩
	*/
    public function rate(){

        $agency_id = $this->_param('id') ? intval($this->_param('id')) : '';
        $tp = $this->_param('tp') ? intval($this->_param('tp')) : '';
        if(!is_numeric($agency_id)){
            exit;
        }
        
		$user_id = $_SESSION['user_info']['pkid'];
    	if($user_id==''){
            $msg = mb_convert_encoding("请登录后点评","GB2312","UTF-8");
            print "<script language=\"javascript\">alert('$msg');location.href='/Member/User/login';</script>";
            exit;
    	}
        
        $agency = new Model("Agency");
        if($tp==1){
        	$agency->query("UPDATE agency SET good_rate=good_rate+1 WHERE pkid=".$agency_id);
        }elseif($tp==2){
        	$agency->query("UPDATE agency SET bad_rate=bad_rate+1 WHERE pkid=".$agency_id);
        }
        redirect('/Member/Agency/show/id/'.$agency_id);
    }
    
    
    public function review(){

        $agency_id = $this->_param('id') ? intval($this->_param('id')) : '';
        if(!is_numeric($agency_id)){
            exit;
        }
        //get agency data
        $agency = new Model("Agency");
        $sql = "SELECT ag.*,ab.title as brand_title,pc.name as area_name FROM agency ag LEFT JOIN agency_brand ab ON ag.brand_id=ab.pkid ".
            " LEFT JOIN province_city pc ON ag.area_id=pc.item_id WHERE ag.pkid=".$agency_id;
        $data_agency = $agency->query($sql);
        $this->assign('data_agency',$data_agency[0]);

        $agency_comment = new Model("AgencyComment");
        $sql = "SELECT * FROM agency_comment WHERE agency_id=".$agency_id." order by ctime desc limit 1 ";
        $data_review = $agency_comment->query($sql);
        $this->assign('data_review',$data_review[0]);

        $sql = "SELECT avg(score) as avg_score FROM agency_comment WHERE agency_id=".$agency_id;
        $data_score = $agency_comment->query($sql);
        $this->assign('data_score',intval($data_score[0]['avg_score'])+1);

        $this->assign('user_info',$_SESSION['user_info']);

        $this->display();
    }

    public function review_done(){

        if(empty($_SESSION['user_info'])){
            $msg = mb_convert_encoding("请登录后点评","GB2312","UTF-8");
            print "<script language=\"javascript\">alert('$msg');location.href='/Member/User/login';</script>";
            exit;
        }

        $agency_id = $this->_param("agency_id") ? $this->_param("agency_id") : '';
        if(empty($agency_id)){
            print "<script language=\"javascript\">history.back();</script>";
            exit;
        }
        $user_id = $_SESSION['user_info']['pkid'];

        $agency_order = new Model("AgencyOrder");

        $cur_time = date("Y-m-d H:i:s");
        $sql = "SELECT order_id FROM agency_order WHERE agency_id=".$agency_id." AND user_id=".$user_id.
            " AND bespeak_date<='$cur_time' AND cancel_time IS NULL ORDER BY bespeak_date asc limit 0,1";
        $data = $agency_order->query($sql);

       // if(!empty($data[0]['order_id'])){
            //学生获得积分8
            $user_score = new Model("UserScore");
            $user_score->create();
            $user_score->user_id = $user_id;
            $user_score->order_id = $data[0]['order_id'] ? $data[0]['order_id'] : '';
            $user_score->score = 8;
            $user_score->s_type = 7;
            $user_score->mark = "评价机构获得8积分";
            $user_score->ctime = date("Y-m-d H:i:s");
            $user_score->add();

            //记录点评内容
            $agency_comment = new Model("AgencyComment");
            $agency_comment->create();
            $agency_comment->order_id = $data[0]['order_id'] ? $data[0]['order_id'] : '';
            $agency_comment->agency_id = $agency_id;
            $agency_comment->user_id = $user_id;
            $agency_comment->score = $this->_param('score');
            $agency_comment->comment = $this->_param('comment');
            $agency_comment->bespeaked = $data[0]['order_id'] ? 1 : 2;
            $agency_comment->reply_status = 'N';
            $agency_comment->ctime = date('Y-m-d H:i:s');
            $agency_comment->add();

            $msg = mb_convert_encoding("评价成功","GB2312","UTF-8");
        /*}else{
            $msg = mb_convert_encoding("请在确认预约结束后点评","GB2312","UTF-8");
        }*/
        print "<script language=\"javascript\">alert('$msg');location.href='/Member/Agency/review/id/$agency_id';</script>";
        exit;
    }
	
	/*
	* 机构页面点评
	*/
    public function comment_done(){

        $agency_id = $this->_param("agency_id") ? $this->_param("agency_id") : '';
        $user_id = $_SESSION['user_info']['pkid'];
        //记录点评内容
        $agency_comment = new Model("AgencyComment");
        $agency_comment->create();
        $agency_comment->agency_id = $agency_id;
        $agency_comment->user_id = $user_id;
        $agency_comment->comment = $this->_param('comment');
        $agency_comment->reply_status = 'N';
        $agency_comment->ctime = date('Y-m-d H:i:s');
        $agency_comment->add();

        $msg = mb_convert_encoding("评价成功","GB2312","UTF-8");
        print "<script language=\"javascript\">alert('$msg');location.href='/Member/Agency/show/id/$agency_id';</script>";
        exit;
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
    private function getCourseSelect($cat_id){

        $course = D('AgencyCourse');
        $list = $course->where("category_id=".$cat_id)->order("pkid asc")->select();
        /*foreach($list as $k=>$v){
              $data[$v['pkid']] = $v['title'];
          }*/
        return $list;
    }
	
	private function getComment($agency_id){
		$AgencyComment = D('AgencyComment');
		$sql = "SELECT ac.comment,ac.score+1 as score,ac.ctime,u.nickname,u.avatar,ac.user_id,ac.bespeaked FROM agency_comment ac ".
				" LEFT JOIN user u ON ac.user_id=u.pkid ".
				" WHERE ac.agency_id=".$agency_id." order by ac.ctime desc";//echo $sql;die;
        $list = $AgencyComment->query($sql);
        return $list;
	}
	
	
	
}