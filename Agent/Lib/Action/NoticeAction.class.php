<?php

class NoticeAction extends Action {
    public function index(){
    	$map = array();
    	$map_sql = 'pkid>0 ';
		
		$map['content'] = !empty($_POST['content']) ? $_POST['content'] : (!empty($_GET['content']) ? $_GET['content'] : '');
		
		$sql = "SELECT * FROM notice n WHERE n.pkid>0 ";
		$sql_c = "SELECT count(n.pkid) as num FROM notice n WHERE n.pkid>0 ";
		
		$where = '';
		if(!empty($map['content'])){
			$where .= " AND n.content like '%".$map['content']."%' ";
		}
		
    		$this->assign('map',$map);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		$notice = new Model("Notice");

		$sql .= $where." ORDER BY n.ctime desc limit ".($cur_page-1)*$page_size.",".$page_size;
		$list = $notice->query($sql);
		
		foreach($list as $k=>$v){
			$list[$k]['content'] = nl2br($v['content']);
            $list[$k]['grade_id_list'] = explode(',',$v['grade_id']);
		}
		$this->assign('notice_list',$list);
		import("ORG.Util.Page");
		
		$count_num = $notice->query($sql_c.$where);
		$count = $count_num[0]['num'];
		
		$Page = new Page($count,$page_size);
		
		if($map){
			foreach($map as $key=>$val) {
			    $Page->parameter   .=   "$key=".urlencode($val).'&';
			}
		}
		
		$show       = $Page->show();
		$this->assign('page',$show);

        $this->assign('grade_list',$this->gradeSet());
		$this->display();
    }
    
    public function add(){
        $this->assign('grade_list',D('Grade')->where('pkid > 0')->select());
		$this->display();
    }
    
    public function save(){
  	   $notice = new Model("Notice");
       $notice->create();
       $notice->grade_id = implode(',',$this->_param('grade_id'));
       $notice->title = $this->_param('title');
       $notice->content = $this->_param('content');
       $notice->ctime = date('Y-m-d H:i:s');
       $notice->add();
    	redirect("/Backend/Notice/index");
    }

    public function edit(){
        $arr_form = D('Notice')->where('pkid = '.$this->_param('pkid'))->find();
        $this->assign('arr_form',$arr_form);
        $arr_grade_checked = explode(',',$arr_form['grade_id']);

        $grade_list = D('Grade')->where('pkid > 0')->select();
        foreach($grade_list as $k=>$v){
            if(in_array($v['pkid'],$arr_grade_checked)){
                $grade_list[$k]['checked'] = 'checked';
            }
        }
        $this->assign('grade_list',$grade_list);
        $this->display();
    }

    public function renew(){
        $notice = new Model("Notice");
        $notice->create();
        $notice->grade_id = implode(',',$this->_param('grade_id'));
        $notice->title = $this->_param('title');
        $notice->content = $this->_param('content');
        $notice->ctime = date('Y-m-d H:i:s');
        $notice->save();
        redirect("/Backend/Notice/index");
    }

    public function remove(){
        D('Notice')->where('pkid='.$this->_param('pkid'))->delete();
        redirect("/Backend/Notice/index");
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