<?php

class KechengbaoAction extends Action {
    public function index(){
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
		$page_size = 5;
		
		$kechengbao = new Model("Kechengbao"); 
		if($map){
			$list = $kechengbao->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $kechengbao->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		
		
		$this->assign('lesson_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $kechengbao->where($map_sql)->count();
		}else{
			$count  = $kechengbao->count();
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
		$this->assign('brandList', $this->brandList());
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('arr_begin_type',array(1=>'七天内开班',2=>'十五天内开班',3=>'约定开班时间'));
		$this->assign('brand_value', '');
    	$this->assign('brandList', $this->brandList());
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
        $pno = $this->_param('pno') ? $this->_param('pno') : 1;
    	$kechengbao = new Model("Kechengbao");
    	$arr_form = $kechengbao->query('SELECT * FROM kechengbao WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);

    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('arr_begin_type',array(1=>'七天内开班',2=>'十五天内开班',3=>'约定开班时间'));
		$this->assign('brandList', $this->brandList());
    	$this->assign('brand_value', $arr_form[0]['brand_id']);
    	$this->assign('begin_type_value', $arr_form[0]['begin_type']);
    	$this->assign('value', $arr_form[0]['status']);
		
		$arr_agency = explode(',',$arr_form[0]['agency_id']);
		$brand_id = $arr_form[0]['brand_id'];
		$agency = new Model("Agency");
		$list = $agency->where("status='Y' AND brand_id = ".$brand_id)->order('title asc')->select();
		foreach($list as $k=>$v){
			if(in_array($v['pkid'],$arr_agency)){
				$list[$k]['checked'] = 'checked';
			}
		}
		$this->assign('agencyList', $list);
        $this->assign('pno', $pno);
		$this->display();
    }
    
    public function save(){
    	
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg','JPG','gif','jpeg');// 设置附件上传类型
		$up_screenshot_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Kechengbao/';// 设置附件上传目录;
		$upload->savePath =  $up_screenshot_path;
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		
		$kechengbao = new Model("Kechengbao");
		$kechengbao->create();
		$agency_id = $this->_param('agency_id');
		$kechengbao->agency_id = implode(',',$agency_id);
		$kechengbao->status = 'N';
		$kechengbao->ctime = date("Y-m-d H:i:s");
		$kechengbao->image = '/Public/Upload/Kechengbao/'.$info[0]['savename'];
		
		$begin_type = $this->_param('begin_type');
		$kechengbao->begin_type = $begin_type[0];
		$kecheng_id = $kechengbao->add();

        //添加课程与机构的绑定关系
        $kecheng_agency = new Model("KechengAgency");
        foreach($agency_id as $v){
            $kecheng_agency->create();
            $kecheng_agency->kecheng_id = $kecheng_id;
            $kecheng_agency->agency_id = $v;
            $kecheng_agency->ctime = date("Y-m-d H:i:s");
            $kecheng_agency->add();
        }

    	redirect("/Backend/Kechengbao/index");
    }
    
    
    public function renew(){
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg','JPG','gif','jpeg');// 设置附件上传类型
		$up_screenshot_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Kechengbao/';// 设置附件上传目录;
		$upload->savePath =  $up_screenshot_path;
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			//$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		
		$kechengbao = new Model("Kechengbao");
		$data['pkid'] = $this->_param('pkid');
		$data['brand_id'] = $this->_param('brand_id');
		$agency_id = $this->_param('agency_id');
		$data['agency_id'] = implode(',',$agency_id);
		$data['title'] = $this->_param('title');
		$begin_type = $this->_param('begin_type');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$data['market_price'] = $this->_param('market_price');
		$data['yitu_price'] = $this->_param('yitu_price');
		$data['lesson_times'] = $this->_param('lesson_times');
		$data['lesson_hours'] = $this->_param('lesson_hours');
		$data['class_begin'] = $this->_param('class_begin');
		$data['begin_type'] = $begin_type[0];
		$data['textbooks'] = $this->_param('textbooks');
		$data['lectuer'] = $this->_param('lectuer');
		$data['lesson_content'] = $this->_param('lesson_content');
		if($info[0]['savename']){
			$data['image'] = '/Public/Upload/Kechengbao/'.$info[0]['savename'];
		}
		//print_r($data);die;
		$kechengbao->save($data);


        //添加课程与机构的绑定关系
        $kecheng_agency = new Model("KechengAgency");
        $kecheng_agency->where('kecheng_id='.$data['pkid'])->delete();
        foreach($agency_id as $v){
            $kecheng_agency->create();
            $kecheng_agency->kecheng_id = $data['pkid'];
            $kecheng_agency->agency_id = $v;
            $kecheng_agency->ctime = date("Y-m-d H:i:s");
            $kecheng_agency->add();
        }

		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Kechengbao/index/p/".$this->_param('pno'));
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$kechengbao = new Model("Kechengbao");
		
		$kechengbao->query('DELETE FROM kechengbao WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Kechengbao/index",1,$msg);
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$kechengbao = new Model("Kechengbao");
		
		$kechengbao->query("UPDATE kechengbao SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Kechengbao/index",1,$msg);
    	//redirect("/Backend/Kechengbao/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$kechengbao = new Model("Kechengbao");
		
		$kechengbao->query("UPDATE kechengbao SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Kechengbao/index",1,$msg);
    	//redirect("/Backend/Kechengbao/index");
    }
    
	public function brandList(){
		
		$agency = new Model("Agency");
		$a_list = $agency->field('brand_id')->where("status='Y' AND sign_type =1")->select();
		foreach($a_list as $k=>$v){
			$bId[] = $v['brand_id'];
		}
		
		$bStr = implode(',',$bId);
		
		$agency_brand = new Model("AgencyBrand");
		$list = $agency_brand->where("status='Y' and pkid IN(".$bStr.")")->order('title asc')->select();
		foreach($list as $k=>$v){
			$brandList[$v['pkid']] = $v['title'];
		}
		return $brandList;
	}
    
	public function getAgencyCheckbox(){
		$brand_id = $this->_get('brand_id');
		$agency = new Model("Agency");
		$list = $agency->where("status='Y' AND brand_id = ".$brand_id)->order('title asc')->select();
		/*foreach($list as $k=>$v){
			$agencyList[$v['pkid']] = $v['title'];
		}*/
		//$this->assign('agencyList',$agencyList);
		$this->assign('agencyList',$list);
		$this->display('agency_checkbox');
	}
}
?>