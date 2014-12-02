<?php

class CustomVoteAction extends Action {
    public function index(){
    	$map = array();
		$map['vote'] = !empty($_POST['vote']) ? $_POST['vote'] : (!empty($_GET['vote']) ? $_GET['vote'] : '');
		$map_sql = "SELECT cv.vote,cv.ctime,u.login_email,u.nickname,u.mobile,u.truename,u.avatar,u.is_teacher FROM custom_vote cv ".
					" LEFT JOIN user u ON cv.user_id=u.pkid where cv.pkid>0 ";
		$map_sql_count = "SELECT COUNT(pkid) AS num FROM custom_vote WHERE pkid>0 ";
		if(!empty($map['vote'])){
		 	$map_sql .= " AND cv.vote='".$map['vote']."'";
		 	$map_sql_count .= " AND vote='".$map['vote']."'";
		}
		
    	$this->assign('vote',$map['vote']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 6;
		
		$vote = new Model("CustomVote"); 
		$map_sql .= "limit ".($cur_page-1)*$page_size.",".$page_size;
		$list = $vote->query($map_sql);
		
		$this->assign('vote_list',$list);
		import("ORG.Util.Page");
		//echo $map_sql_count;
		//echo $map_sql;
		$count_row  = $vote->query($map_sql_count);
		$count = $count_row[0]['num'];
		
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);
    	$this->assign('arr_vote', array('Y'=>'需要','N'=>'不需要'));
		
    	$this->assign('count',$count);
    	$vote_yes_res = $vote->query("SELECT count(pkid) as yes_num FROM custom_vote WHERE vote='Y'");
    	$yes_num = $vote_yes_res[0]['yes_num'];
    	$this->assign('vote_yes',$yes_num);
    	$this->assign('vote_no',$count - $yes_num);
    	$vote_yes_rate= round(($yes_num/$count)*100,2);
    	$this->assign('vote_yes_rate',$vote_yes_rate);
    	
		$this->display();
    }
    
 

}
?>