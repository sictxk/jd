<?php

class TeacherScoreAction extends Action {
    public function index(){
    	$map = array();
		$map['nickname'] = !empty($_POST['nickname']) ? $_POST['nickname'] : (!empty($_GET['nickname']) ? $_GET['nickname'] : '');
		
		$map_sql = "SELECT tr.*,t.truename,t.email,oi.order_sn FROM teacher_review  tr ".
					" LEFT JOIN teacher t ON tr.teacher_id=t.pkid LEFT JOIN order_info oi ON tr.order_id=oi.order_id WHERE tr.pkid>0  ";
		$map_sql_count = "SELECT count(tr.pkid) as num FROM teacher_review tr ".
					" LEFT JOIN teacher t ON tr.teacher_id=t.pkid WHERE tr.pkid>0   ";
		if(!empty($map['nickname'])){
			$map_sql .= "AND t.truename like '%".$map['nickname']."%'";
			$map_sql_count .= "AND t.truename like '%".$map['nickname']."%'";
		}
		
    	$this->assign('map',$map);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= " ORDER BY tr.ctime desc LIMIT ".($cur_page-1)*$page_size.",".$page_size;
		
		$user_score = new Model("UserScore"); 
		
		$list = $user_score->query($map_sql);
		
		
		$this->assign('data_list',$list);
		
		import("ORG.Util.Page");
		$data_count  = $user_score->query($map_sql_count);
		$count = $data_count[0]['num'];
		
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
    
}
?>