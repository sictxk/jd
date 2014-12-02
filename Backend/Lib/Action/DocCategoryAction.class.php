<?php

class DocCategoryAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 and parent_id=1 ';

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
		$page_size  = 10;
		
		$doc_category = new Model("DocCategory");
		
		if($map){
			$list = $doc_category->where($map_sql)->order('pkid asc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $doc_category->order('pkid asc')->page($cur_page.",".$page_size)->select();
		}

		$data_list = array();
		$d_category = new Model("DocCategory");
		foreach($list as $key=>$val){
			$data_list[] = $val;
			$sub_list = array();
			$sub_list = $d_category->where("parent_id=".$val['pkid'])->select();
			foreach($sub_list as $k=>$v){
				$data_list[] = $v;
			}
		}
		
		
		$this->assign('category_list',$data_list);
		import("ORG.Util.Page");
		if($map){
			$count  = $doc_category->where($map_sql)->count();
		}else{
			$count  = $doc_category->count();
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
    	
    	
    	$arr_parent_doc_category = $this->getParentCategory();
    	
    	$this->assign('parent_id', $arr_parent_doc_category);
    	
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$doc_category = new Model("DocCategory");
    	$arr_form = $doc_category->query('SELECT * FROM doc_category WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	$this->assign('value', $arr_form[0]['status']);
    	$this->assign('parent_id', $arr_form[0]['parent_id']);
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));

    	$arr_parent_doc_category = $this->getParentCategory();
    	
    	$this->assign('parent_ids', $arr_parent_doc_category);
    	
    	
		$this->display();
    }
    
    public function save(){
    	
		$doc_category = new Model("DocCategory");
		
		$data_parent = $doc_category->query("SELECT level+1 as level FROM doc_category WHERE pkid=".$this->_param('parent_id'));
		
		$doc_category->create();
		$doc_category->level = $data_parent[0]['level'];
		$doc_category->add();
		
    	redirect("/Backend/DocCategory/index");
    }
    
    
    public function renew(){
    	
		$doc_category = new Model("DocCategory");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['parent_id'] = $this->_param('parent_id');	
		
		
		$data_parent = $doc_category->query("SELECT level+1 as level FROM doc_category WHERE pkid=".$data['parent_id']);
		$data['level'] = $data_parent[0]['level'];
		
		$status = $this->_param('status');
		$data['status'] = $status[0];
		$doc_category->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/DocCategory/index",1,$msg);
    	//redirect("/Backend/DocCategory/index");
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$doc_category = new Model("DocCategory");
		//进行原生的SQL查询
		$doc_category->query('DELETE FROM doc_category WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/DocCategory/index",1,$msg);
    	//redirect("/Backend/DocCategory/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$doc_category = new Model("DocCategory");
		//进行原生的SQL查询
		$doc_category->query("UPDATE doc_category SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/DocCategory/index",1,$msg);
    	//redirect("/Backend/DocCategory/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$doc_category = new Model("DocCategory");
		//进行原生的SQL查询
		$doc_category->query("UPDATE doc_category SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/DocCategory/index",1,$msg);
    	//redirect("/Backend/DocCategory/index");
    }
    
    public function getParentCategory(){
		$doc_category = new Model("DocCategory");
		//进行原生的SQL查询
		$data_list = $doc_category->query("Select * FROM doc_category WHERE parent_id=0 or parent_id=1 ORDER BY pkid ASC ");
    	
    	foreach($data_list as $key=>$val){
    		$cate_list[$val['pkid']] = $val['title'];
    	}
    	return $cate_list;
    }
    
}
?>