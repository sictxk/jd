<?php

class AdminAction extends Action {
    public function index(){
    	$map = array();
		$map['account'] = !empty($_POST['account']) ? $_POST['account'] : (!empty($_GET['account']) ? $_GET['account'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		
		$map_sql = 'pkid>0 ';
		if(!empty($map['account'])){
			$map_sql .= "AND account like '%".$map['account']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		}
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 3;
		
		$admin = new Model("Admin"); 
		if($map){
			$list = $admin->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $admin->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		
		
		$this->assign('admin_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $admin->where($map_sql)->count();
		}else{
			$count  = $admin->count();
		}
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
    	
    	$this->assign('status', array('Y'=>'正常','N'=>'禁用'));
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'正常','N'=>'禁用'));
    	
		$this->display();
    }
    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$admin = new Model("Admin");
    	$arr_form = $admin->query('SELECT * FROM admin WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'正常','N'=>'禁用'));
    	$this->assign('value', $arr_form[0]['status']);
    	
		$this->display();
    }
    
    public function save(){
    	
		$admin = new Model("Admin");
		$admin->create();
		$admin->password = md5($this->_param('password'));
		$admin->ctime = date('Y-m-d H:i:s');
		$admin->add();
		
		//$msg = mb_convert_encoding("添加成功","UTF-8","GB2312");
    	//redirect("/Backend/Admin/index",2,$msg);
    	redirect("/Backend/Admin/index");
    }
    
    
    public function renew(){
    	
		$admin = new Model("Admin");
		$data['pkid'] = $this->_param('pkid');
		$data['account'] = $this->_param('account');
		if(strlen($this->_param('password')) < 32){
			$data['password'] = md5($this->_param('password'));
		}
		$data['email'] = $this->_param('email');
		$data['mobile'] = $this->_param('mobile');
		$data['truename'] = $this->_param('truename');
		$status = $this->_param('status');
		$data['status'] = $status[0];
		

		
		$admin->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Admin/index",2,$msg);
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$admin = new Model("Admin");
		
		$admin->query('DELETE FROM admin WHERE pkid='.$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Admin/index",1,$msg);
    	//redirect("/Backend/Admin/index");
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$admin = new Model("Admin");
		
		$admin->query("UPDATE admin SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Admin/index",1,$msg);
    	//redirect("/Backend/Admin/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$admin = new Model("Admin");
		
		$admin->query("UPDATE admin SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Admin/index",1,$msg);
    	//redirect("/Backend/Admin/index");
    }
    
    
    
}
?>