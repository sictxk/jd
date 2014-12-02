<?php

class UserAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';

		$map['truename'] = !empty($_POST['truename']) ? $_POST['truename'] : (!empty($_GET['truename']) ? $_GET['truename'] : '');
		$map['status'] = !empty($_POST['status']) ? $_POST['status'] : (!empty($_GET['status']) ? $_GET['status'] : '');
		if(!empty($map['truename'])){
			$map_sql .= "AND truename like '%".$map['truename']."%'";
		}
		if(!empty($map['status'])){
		 	$map_sql .= " AND status='".$map['status']."'";
		 }
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 10;
		
		$user = new Model("User");
		
		if($map){
			$list = $user->where($map_sql)->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}else{
			$list = $user->order('ctime desc')->page($cur_page.",".$page_size)->select();
		}
		
		
		$this->assign('user_list',$list);
		import("ORG.Util.Page");
		if($map){
			$count  = $user->where($map_sql)->count();
		}else{
			$count  = $user->count();
		}
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
    	
		$this->display();
    }

    public function add(){

        $this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('gender', array('M'=>'男','F'=>'女'));
        $this->assign('grade_id', $this->gradeSet());

        $this->display();
    }

    public function save(){
        
        $user = new Model("User");
        $user->create();
        $user->ctime = date("Y-m-d H:i:s");
        $user->status = 2;
        $user->add();

        redirect("/Backend/User/index");
    }

    public function edit(){
    	$pkid = $this->_param('pkid');
    	$user = new Model("User");
    	$arr_form = $user->query('SELECT * FROM  user  WHERE pkid='.$pkid);
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('gender_value', $arr_form[0]['gender']);
        $this->assign('grade_value', $arr_form[0]['grade_id']);
    	$this->assign('gender', array('M'=>'男','F'=>'女'));

        $this->assign('grade_id', $this->gradeSet());

		$this->display();
    }

    public function renew(){
        
        $user = new Model("User");
        $data['pkid'] = $this->_param('pkid');
        $data['account'] = $this->_param('account');
        $data['truename'] = $this->_param('truename');
        $gender = $this->_param('gender');
        $data['gender'] = $gender[0];
        $data['email'] = $this->_param('email');
        $data['mobile'] = $this->_param('mobile');
        $data['password'] = $this->_param('password');
        $data['parents'] = $this->_param('parents');
        $data['school'] = $this->_param('school');
        $data['grade_id'] = $this->_param('grade_id');
        //print_r($data);die;
        $user->save($data);

        $msg = mb_convert_encoding("OK","UTF-8","GB2312");
        redirect("/Backend/User/index",1,$msg);
    }

    private function gradeSet(){
        $Grade = D('Grade');
        $arr_grade =  $Grade->where('pkid > 0')->select();

        $arr_select = array();
        foreach($arr_grade as $key=>$val){
            $arr_select[$val['pkid']] = $val['title'];
        }

        return $arr_select;
    }
}
?>