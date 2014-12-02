<?php

class DocumentAction extends Action {
    public function index(){
    	$map = array();
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		$map['competitive'] = !empty($_POST['competitive']) ? $_POST['competitive'] : (!empty($_GET['competitive']) ? $_GET['competitive'] : '');
		$map['category_id'] = !empty($_POST['category_id']) ? $_POST['category_id'] : (!empty($_GET['category_id']) ? $_GET['category_id'] : '');
		$map_sql = 'pkid>0 ';
		$map_sql_list = "SELECT d.pkid,d.category_id,d.title,d.path,d.hits_num,d.status,d.ctime,d.score,d.competitive,dc.title as category_title FROM document d LEFT JOIN doc_category dc ON d.category_id=dc.pkid WHERE d.pkid>0 ";

		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
			$map_sql_list .= "AND d.title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 	$map_sql_list .= " AND d.status='".$map['status']."'";
		}
		if(!empty($map['competitive'])){
		 	$map_sql .= " AND competitive='".$map['competitive']."'";
		 	$map_sql_list .= " AND d.competitive='".$map['competitive']."'";
		}
		if(!empty($map['category_id'])){
		 	$map_sql .= " AND category_id='".$map['category_id']."'";
		 	$map_sql_list .= " AND d.category_id='".$map['category_id']."'";
		}
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	$this->assign('competitive_value',$map['competitive']);
    	$this->assign('category_id',$map['category_id']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 12;
		
		$document = new Model("Document"); 
		/*if($map){
			$list = $document->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $document->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}*/
		$map_sql_list .= " ORDER BY d.ctime desc limit ".($cur_page-1)*$page_size.",".$page_size;
		$list = $document->query($map_sql_list);
		
		$this->assign('document_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $document->where($map_sql)->count();
		}else{
			$count  = $document->count();
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
    	$this->assign('is_free', array('Y'=>'是','N'=>'否'));
    	$this->assign('competitive', array('Y'=>'是','N'=>'否'));
    	$this->assign('doc_category', $this->getDocCategory());
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('doc_category', $this->getDocCategory());
    	$this->assign('is_free', array('Y'=>'是','N'=>'否'));
    	
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$document = new Model("Document");
    	$arr_form = $document->query('SELECT * FROM document WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	$this->assign('doc_category', $this->getDocCategory());
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	$this->assign('is_free', array('Y'=>'是','N'=>'否'));
    	$this->assign('value', $arr_form[0]['status']);
    	$this->assign('category_id', $arr_form[0]['category_id']);
    	
		$this->display();
    }
    
    public function save(){
    	
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728*5 ;// 设置附件上传大小
		$upload->allowExts  = array('doc', 'docx','xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'rar','zip');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Document/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
    	
		$document = new Model("Document");
		$document->create();
		$document->path = '/Public/Upload/Document/'.$info[0]['savename'];
		$document->size = $info[0]['size']/1024;
		$document->ext = $info[0]['extension'];
		$document->user_id = 0;
		
		$original_name = $info[0]['name'];
		$document->original_name = $original_name;
		$title = rtrim(str_replace($arr_allowExts, "", $original_name),".");
		$document->title = $title;
		
		$document->ctime = date("Y-m-d H:i:s");
		$document->add();
		
		//$msg = mb_convert_encoding("添加成功","UTF-8","GB2312");
    	//redirect("/Backend/Document/index",1,$msg);
    	redirect("/Backend/Document/index");
    }
    
    
    public function renew(){
		
		
		$document = new Model("Document");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['score'] = $this->_param('score');
		$data['summary'] = $this->_param('summary');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$data['competitive'] = 'N';
		$data['category_id'] = $this->_param('category_id');
		

		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728*5 ;// 设置附件上传大小
		$upload->allowExts  = array('doc','docx','xls','xlsx','ppt','pptx','pdf', 'rar','zip');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Document/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			//$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		//print_r($info);die;
		if(!empty($info[0]['savename'])){
			$data['path'] = '/Public/Upload/Document/'.$info[0]['savename'];
			$data['size'] = $info[0]['size']/1024;
			$data['ext'] = $info[0]['extension'];
			$data['user_id'] = 0;
			$data['original_name'] = $info[0]['name'];
		}

		
		$document->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Document/index",1,$msg);
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$document = new Model("Document");
		
		
		$data = $document->where("pkid=".$pkid)->select();
		$root_path = dirname(dirname(dirname(dirname(__FILE__))));
		$file_path = $root_path.$data[0]['path'];
		
		@unlink($file_path);
		
		$document->query('DELETE FROM document WHERE pkid='.$pkid);
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect($_SERVER["HTTP_REFERER"],1,$msg);
    	//redirect("/Backend/Document/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$document = new Model("Document");
		
		$document->query("UPDATE document SET status='N' WHERE pkid=".$pkid);
    	
		$row_document = $document->where("pkid=".$pkid)->select();
		//上传者得分
		if($row_document[0]['user_id']!=0){
			$user_score = new Model("UserScore");
			$user_score->create();
			$user_score->score = '-'.$row_document[0]['score'];
			$user_score->user_id = $row_document[0]['user_id'];
			$user_score->s_type = 5;//上传的资料被下架
			$user_score->mark = "上传的资料被下架：".$row_document[0]['title'];
			$user_score->ctime = date("Y-m-d H:i:s");
			$user_score->add();
		}
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect($_SERVER["HTTP_REFERER"],1,$msg);
    	//redirect("/Backend/Document/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$document = new Model("Document");
		
		$document->query("UPDATE document SET status='Y' WHERE pkid=".$pkid);
    	
		$row_document = $document->where("pkid=".$pkid)->select();
		//上传者得分
		if($row_document[0]['user_id']!=0){
			$user_score = new Model("UserScore");
			$user_score->create();
			$user_score->score = $row_document[0]['score'];
			$user_score->user_id = $row_document[0]['user_id'];
			$user_score->s_type = 3;//上传资料
			$user_score->mark = "上传的资料审核通过：".$row_document[0]['title'];
			$user_score->ctime = date("Y-m-d H:i:s");
			$user_score->add();
		}
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect($_SERVER["HTTP_REFERER"],1,$msg);
    	//redirect("/Backend/Document/index");
    }
    

    
    public function competitive(){
    	
    	$pkid = $this->_param('pkid'); 
    	$flg = $this->_param('flg'); 
		$document = new Model("Document");
		
		$document->query("UPDATE document SET competitive='".$flg."' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect($_SERVER["HTTP_REFERER"],1,$msg);
    }
    
    
    
    public function getDocCategory(){
		$doc_category = new Model("DocCategory");
		//进行原生的SQL查询
		$data_list = $doc_category->query("Select * FROM doc_category WHERE parent_id=1 ORDER BY pkid ASC ");
		
    	$cate_list = array();
    	foreach($data_list as $key=>$val){
    		$sub_list = array();
    		$cate_list[$val['pkid']] = "--".$val['title']."--";
			$sub_list = $doc_category->where("parent_id=".$val['pkid'])->select();
			foreach($sub_list as $k=>$v){
				$cate_list[$v['pkid']] = $v['title'];
			}
    		
    	}
    	return $cate_list;
    }
    
}
?>