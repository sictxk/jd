<?php

class GradeAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';

		$map['title'] = !empty($_POST['title']) ? $_POST['title'] : (!empty($_GET['title']) ? $_GET['title'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');

		if(!empty($map['title'])){
			$map_sql .= "AND title like '%".$map['title']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 12;
		
		$Grade = new Model("Grade");
		
		if($map){
			$list = $Grade->where($map_sql)->order('pkid desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $Grade->order('pkid desc')->page($cur_page.",".$page_size)->select();
		}
		
		
		$this->assign('Grade_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $Grade->where($map_sql)->count();
		}else{
			$count  = $Grade->count();
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
    	$Grade = new Model("Grade");
    	$arr_form = $Grade->query('SELECT * FROM grade WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	$this->assign('value', $arr_form[0]['status']);
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));

    	
		$this->display();
    }
    
    public function save(){
    	
		$Grade = new Model("Grade");
		
		$Grade->create();
		$Grade->add();
		
		//$msg = mb_convert_encoding("添加成功","UTF-8","GB2312");
    	//redirect("/Backend/Grade/index",2,$msg);
    	redirect("/Backend/Grade/index");
    }
    
    
    public function renew(){
    	
		$Grade = new Model("Grade");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$Grade->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Grade/index",2,$msg);
    	redirect("/Backend/Grade/index");
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Grade = new Model("Grade");
		//进行原生的SQL查询
		$Grade->query('DELETE FROM grade WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Grade/index",1,$msg);
    	//redirect("/Backend/Grade/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Grade = new Model("Grade");
		//进行原生的SQL查询
		$Grade->query("UPDATE Grade SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Grade/index",1,$msg);
    	//redirect("/Backend/Grade/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$Grade = new Model("Grade");
		//进行原生的SQL查询
		$Grade->query("UPDATE Grade SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Grade/index",1,$msg);
    	//redirect("/Backend/Grade/index");
    }
}
?>