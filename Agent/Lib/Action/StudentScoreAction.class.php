<?php

class StudentScoreAction extends Action {
    public function index(){
    	$map = array();
		$map['nickname'] = !empty($_POST['nickname']) ? $_POST['nickname'] : (!empty($_GET['nickname']) ? $_GET['nickname'] : '');
		
		$map_sql = "SELECT us.score,us.ctime,u.nickname,u.login_email,st.title as score_type FROM user_score us ".
					" LEFT JOIN user u ON us.user_id=u.pkid LEFT JOIN score_type st ON us.s_type=st.pkid WHERE us.pkid>0 AND us.score>0 AND us.s_type=3 ";
		$map_sql_count = "SELECT count(us.pkid) as num FROM user_score us ".
					" LEFT JOIN user u ON us.user_id=u.pkid LEFT JOIN score_type st ON us.s_type=st.pkid WHERE us.pkid>0 AND us.score>0 AND us.s_type=3 ";
		if(!empty($map['nickname'])){
			$map_sql .= "AND u.nickname like '%".$map['nickname']."%'";
			$map_sql_count .= "AND u.nickname like '%".$map['nickname']."%'";
		}
		
    	$this->assign('map',$map);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= " ORDER BY us.ctime desc LIMIT ".($cur_page-1)*$page_size.",".$page_size;
		
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