<?php

class ChapterAction extends Action {
    public function index(){
    	$map = array();
		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		$map['lesson_id'] = !empty($_POST['lesson_id']) ? $_POST['lesson_id'] : (!empty($_GET['lesson_id']) ? $_GET['lesson_id'] : '');
        $map['video_type'] = !empty($_POST['video_type']) ? $_POST['video_type'] : (!empty($_GET['video_type']) ? $_GET['video_type'] : '');
		$map_sql = 'pkid>0 ';
		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		}
		if(!empty($map['lesson_id'])){
		 	$map_sql .= " AND lesson_id='".$map['lesson_id']."'";
		}
        if(!empty($map['video_type'])){
            $map_sql .= " AND video_type='".$map['video_type']."'";
        }
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
        $this->assign('type_value',$map['video_type']);
    	$this->assign('lesson_id',$map['lesson_id']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 5;
		
		$chapter = new Model("Chapter"); 
		if($map){
			$list = $chapter->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $chapter->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		
		
		$this->assign('chapter_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $chapter->where($map_sql)->count();
		}else{
			$count  = $chapter->count();
		}
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
		$this->assign('pNo',$cur_page);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('video_type', array('1'=>'正课','2'=>'练习',3=>'试看'));
    	$this->assign('arr_lesson', $this->getLesson());
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('video_type', array('1'=>'正课','2'=>'练习',3=>'试看'));
    	$this->assign('arr_lesson', $this->getLesson());
		
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$chapter = new Model("Chapter");
    	$arr_form = $chapter->query('SELECT * FROM chapter WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	$this->assign('arr_lesson', $this->getLesson());
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('video_type', array('1'=>'正课','2'=>'练习',3=>'试看'));

    	$this->assign('value', $arr_form[0]['status']);
        $this->assign('type_value', $arr_form[0]['video_type']);
    	$this->assign('lesson_id', $arr_form[0]['lesson_id']);
    	$this->assign('lessonId', $this->_param('lessonId'));
		$this->assign('pno', $this->_param('pno'));
		$this->display();
    }
    
    public function save(){
    	
		import('ORG.Net.FileManage');
		$upload_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Lesson/Screenshot/';
		if ($_FILES['screenshot']['name'] ) {//展示图
			$filemanage = new FileManage($_FILES['screenshot'], $upload_path );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$screenshot = '/Public/Upload/Lesson/Screenshot/'.date('Ym').'/'. basename($arr_img['path']);
			} else {
				$this->error($filemanage->errormsg);
			}
		}
		
		$upload_path2 = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Document/';
		if ($_FILES['document']['name'] ) {//讲义
			$filemanage = new FileManage($_FILES['document'], $upload_path2 );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$document = '/Public/Upload/Document/'.date('Ym').'/'. basename($arr_img['path']);
			} else {
				$this->error($filemanage->errormsg);
			}
		}
    	
		$chapter = new Model("Chapter");
		$chapter->create();
        $video_type = $this->_param('video_type');
        $chapter->video_type = $video_type[0];
        if($screenshot!=''){
            $chapter->screenshot = $screenshot;
        }
        if($document!=''){
            $chapter->document = $document;
        }
		$chapter->add();
		$pno = $this->_param('pno');
			
    	redirect("/Backend/Chapter/index/p/".$pno);
    }
    
    
    public function renew(){
		
		//print_r($_FILES);//die;
		$chapter = new Model("Chapter");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$status = $this->_param('status');
		$data['status'] = $status[0];

        $video_type = $this->_param('video_type');
        $data['video_type'] = $video_type[0];
		$data['lesson_id'] = $this->_param('lesson_id');
		$data['video_path'] = $this->_param('video_path');


		import('ORG.Net.FileManage');
		$upload_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Lesson/Screenshot/';
		if ($_FILES['screenshot']['name'] ) {//展示图
			$filemanage = new FileManage($_FILES['screenshot'], $upload_path );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$screenshot = '/Public/Upload/Lesson/Screenshot/'.date('Ym').'/'. basename($arr_img['path']);
				$data['screenshot'] = $screenshot;
			} else {
				$this->error($filemanage->errormsg);
			}
		}
		
		$upload_path2 = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Document/';
		if ($_FILES['document']['name'] ) {//讲义
			$filemanage = new FileManage($_FILES['document'], $upload_path2 );
			if ( $filemanage->upload() ) {
				$arr_img['path'] = $filemanage->get_file_path();
				$document = '/Public/Upload/Document/'.date('Ym').'/'. basename($arr_img['path']);
				$data['document'] = $document;
			} else {
				$this->error($filemanage->errormsg);
			}
		}

        $chapter->save($data);
		
	$msg = mb_convert_encoding("OK","UTF-8","GB2312");
	
    	$pno = $this->_param('pno');
    	$lessonId = $this->_param('lessonId');
    	redirect("/Backend/Chapter/index/p/".$pno.'/lesson_id/'.$lessonId,1,$msg);
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$chapter = new Model("Chapter");
		
		$chapter->query('DELETE FROM chapter WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Chapter/index",1,$msg);
		
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$chapter = new Model("Chapter");
		
		$chapter->query("UPDATE chapter SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Chapter/index",1,$msg);

    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$chapter = new Model("Chapter");
		
		$chapter->query("UPDATE chapter SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Chapter/index",1,$msg);

    }
    
    public function getLesson(){
		$lesson = new Model("Lesson");
		//进行原生的SQL查询
		$data_list = $lesson->query("Select * FROM lesson WHERE pkid>0 ORDER BY pkid ASC ");
		
    	$lesson_list = array();
    	foreach($data_list as $key=>$val){
			$lesson_list[$val['pkid']] = $val['title'];
    	}
    	return $lesson_list;
    }
    
}
?>
