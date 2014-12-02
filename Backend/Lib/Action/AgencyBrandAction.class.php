<?php
    	error_reporting(E_ALL); 
    	ini_set('display_errors', 1);
header("Content-type:text/html;charset=utf-8");		
class AgencyBrandAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';
		
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');

		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%' OR intro like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }

    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);

    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 6;
		$agency_brand = new Model("AgencyBrand");

		if($map){
			$list = $agency_brand->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $agency_brand->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		foreach($list as $k=>$v){
			$list[$k]['title'] = nl2br($v['title']);
		}
		
		$this->assign('agency_brand_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $agency_brand->where($map_sql)->count();
		}else{
			$count  = $agency_brand->count();
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


		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$agency_brand = new Model("AgencyBrand");
    	$arr_form = $agency_brand->query('SELECT * FROM agency_brand WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('value', $arr_form[0]['status']);
    	

		
		$this->display();
    }
    
    public function save(){

		$agency_brand = new Model("AgencyBrand");
		$result = $agency_brand->where("title='".$this->_param('title')."'")->find();
		if(!empty($result)){
			$msg = "该品牌已存在，不需重复录入.";
			redirect("/Backend/AgencyBrand/index",1,$msg);
			exit;
		}
		
		import('ORG.Net.UploadFile');
		
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/AgencyBrand/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if($_FILES['logo']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$logo = '/Public/Upload/AgencyBrand/'.$info[0]['savename'];
			}
		}

        import('ORG.Net.UploadFile');
		$upload2 = new UploadFile();// 实例化上传类
		$upload2->maxSize  = 3145728 ;// 设置附件上传大小
		$upload2->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path2 = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/BrandPicture/';// 设置附件上传目录;
		$upload2->savePath =  $up_path2;
		$upload2->saveRule =  uniqid();
		
		if($_FILES['picture']['name']!=''){
			if(!$upload2->upload()) {// 上传错误提示错误信息
				$this->error($upload2->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info2 =  $upload2->getUploadFileInfo();
				$picture = '/Public/Upload/BrandPicture/'.$info2[0]['savename'];
			}
		}
		
		/*import('ORG.Net.FileManage');
		$upload_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/BrandPicture/';//echo $upload_path;
		if ($_FILES['picture']['name'] ) {//展示图
			$filemanage = new FileManage($_FILES['picture'], $upload_path );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$picture = '/Public/Upload/BrandPicture/'.date('Ym').'/'. basename($arr_img['path']);
			} else {
				$this->error($filemanage->errormsg);
			}
		}
		echo $picture;die;*/
		$agency_brand->create();
		if($logo!=''){
			$agency_brand->logo = $logo;
		}
		if($picture!=''){
			$agency_brand->picture = $picture;
		}
		$agency_brand->ctime = date('Y-m-d H:i:s');
		$agency_brand->add();
		
    	redirect("/Backend/AgencyBrand/index");
    }
    
    
    public function renew(){
    	
		$agency_brand = new Model("AgencyBrand");

		$result = $agency_brand->where("title='".$this->_param('title')."' AND pkid!=".$this->_param('pkid'))->find();
		if(!empty($result)){
			$msg = "该品牌已存在，不需重复录入.";
			redirect("/Backend/AgencyBrand/edit/pkid/".$this->_param('pkid'),1,$msg);
			exit;
		}
		
		
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['author'] = $this->_param('author');
		$data['source'] = $this->_param('source');
		$data['intro'] = $this->_param('intro');
		
		$data['context'] = $this->_param('context');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/AgencyBrand/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		//print_r($_FILES);
		if($_FILES['logo']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$data['logo'] = '/Public/Upload/AgencyBrand/'.$info[0]['savename'];
			}
		}
		unset($upload);
		
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/BrandPicture/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  uniqid();
		//print_r($_FILES);
		if($_FILES['picture']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info2 =  $upload->getUploadFileInfo();
				$data['picture'] = '/Public/Upload/BrandPicture/'.$info2[0]['savename'];
			}
		}
		
		/*import('ORG.Net.FileManage');
		$upload_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/BrandPicture/';
		if ($_FILES['picture']['name'] ) {//展示图
			$filemanage = new FileManage($_FILES['picture'], $upload_path );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$data['picture'] = '/Public/Upload/BrandPicture/'.date('Ym').'/'. basename($arr_img['path']);
			} else {
				$this->error($filemanage->errormsg);
			}
		}*/
		
		//print_r($data);die;
		$agency_brand->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyBrand/index",2,$msg);
		
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency_brand = new Model("AgencyBrand");
		
		$sql = 'DELETE FROM agency_brand WHERE pkid='.$pkid;
		//echo $sql;die;
		$agency_brand->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyBrand/index",1,$msg);
    	//redirect("/Backend/AgencyBrand/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency_brand = new Model("AgencyBrand");

		$agency_brand->query("UPDATE agency_brand SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyBrand/index",1,$msg);
    	//redirect("/Backend/AgencyBrand/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$agency_brand = new Model("AgencyBrand");

		$agency_brand->query("UPDATE agency_brand SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyBrand/index",1,$msg);
    	//redirect("/Backend/AgencyBrand/index");
    }
    

}
?>