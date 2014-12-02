<?php

class ArticleAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';
		
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
        $map['type'] = !empty($_POST['type']) ? $_POST['type'] : (!empty($_GET['type']) ? $_GET['type'] : '');

		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }
        if(!empty($map['type'])){
            $map_sql .= " AND type=".$map['type'];
        }
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
        $this->assign('type_value',$map['type']);

    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 6;
		$article = new Model("Article");

		if($map){
			$list = $article->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $article->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		foreach($list as $k=>$v){
			$list[$k]['title'] = nl2br($v['title']);
		}
		
		$this->assign('article_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $article->where($map_sql)->count();
		}else{
			$count  = $article->count();
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
        $this->assign('article_type', array(1=>'前台',2=>'商家'));
		
    			
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('article_type', array(1=>'前台',2=>'商家'));


		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$article = new Model("Article");
    	$arr_form = $article->query('SELECT * FROM article WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('article_type', array(1=>'前台',2=>'商家'));
    	$this->assign('value', $arr_form[0]['status']);
        $this->assign('type_value', $arr_form[0]['type']);

		
		$this->display();
    }
    
    public function save(){
    	
    	
    	
		$article = new Model("Article");
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Article/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  $up_path;
		
		if($_FILES['picture']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$picture = '/Public/Upload/Article/'.$info[0]['savename'];
			}
		}
		
		$article->create();
		if($picture!=''){
			$article->picture = $picture;
		}
        $status = $this->_param('status');
        $type = $this->_param('type');
        
        $article->status = $status[0];
        $article->type = $type[0];

		$article->ctime = date('Y-m-d H:i:s');
		$article->add();
		
    	redirect("/Backend/Article/index");
    }
    
    
    public function renew(){
    	
		$article = new Model("Article");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['author'] = $this->_param('author');
		$data['source'] = $this->_param('source');
		$data['intro'] = $this->_param('intro');
		
		$data['context'] = $this->_param('context');
		$status = $this->_param('status');
		$data['status'] = $status[0];

        $type = $this->_param('type');
        $data['type'] = $type[0];
		
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Article/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		//print_r($_FILES);
		if($_FILES['picture']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$data['picture'] = '/Public/Upload/Article/'.$info[0]['savename'];
			}
		}
		
		//print_r($data);die;
		$article->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Article/index",2,$msg);
		
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$article = new Model("Article");
		
		$sql = 'DELETE FROM article WHERE pkid='.$pkid;
		//echo $sql;die;
		$article->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Article/index",1,$msg);
    	//redirect("/Backend/Article/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$article = new Model("Article");

		$article->query("UPDATE article SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Article/index",1,$msg);
    	//redirect("/Backend/Article/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$article = new Model("Article");

		$article->query("UPDATE article SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Article/index",1,$msg);
    	//redirect("/Backend/Article/index");
    }
    

}
?>