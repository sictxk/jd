<?php

class NoteAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'note_id>0 ';
		
		$map['content'] = !empty($_POST['content']) ? $_POST['content'] : (!empty($_GET['content']) ? $_GET['content'] : '');


		$sql = "SELECT n.*,u.truename,u.mobile FROM note n left join teacher t on n.teacher_id = t.pkid left join user u on t.user_id=u.pkid WHERE n.pkid>0 ";
		$sql_c = "SELECT count(n.pkid) as num FROM note n left join teacher t on n.teacher_id = t.pkid left join user u on t.user_id=u.pkid WHERE n.pkid>0 ";
		
		$where = '';
		if(!empty($map['content'])){
			$where .= " AND n.content like '%".$map['content']."%' ";
		}
		
    	$this->assign('map',$map);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		$note = new Model("Note");

		$sql .= $where." ORDER BY n.ctime desc limit ".($cur_page-1)*$page_size.",".$page_size;
		$list = $note->query($sql);
		
		foreach($list as $k=>$v){
			$list[$k]['content'] = nl2br($v['content']);
		}
		
		$this->assign('note_list',$list);
		import("ORG.Util.Page");
		
		$count_num = $note->query($sql_c.$where);
		$count = $count_num[0]['num'];
		
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
    
    
    public function remove(){
    	
    	$note_id = $this->_param('note_id'); 
    	
		$note = new Model("Note");
		
		$note->query('DELETE FROM note WHERE pkid='.$note_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Note/index",1,$msg);
    	//redirect("/Backend/Course/index");
    }
}
?>