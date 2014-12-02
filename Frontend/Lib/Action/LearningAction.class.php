<?php
header("Content-type: text/html; charset=utf-8");
class LearningAction extends Action {
	
	
	/*
	* 学习中心
	*/
    public function index(){
		$this->assign('user_info',$_SESSION['user_info']);
		if(!empty($_SESSION['user_info'])){
			 //获取学生所购科目列表
			 $user_order = D("UserOrder");
			 $cur_date = date("Y-m-d");
			 $sql = "SELECT course_list FROM user_order WHERE expire_date>='".$cur_date."' AND user_id=".$_SESSION['user_info']['pkid'];
			 $course_list = $user_order->query($sql);
			 if(!empty($course_list)){
				 $arr_course_list = array();
				 foreach($course_list as $key=>$val){
				 	 $course_unit = explode(',',$val['course_list']);
				 	 $arr_course_list = array_unique(array_merge($arr_course_list,$course_unit));
				 }
				 //获取课程列表
				 $str_course_list = implode(',',$arr_course_list);
				 $lesson = D("Lesson");
				 $sql = "SELECT l.pkid,l.book_cover,l.title,c.title as course_title FROM lesson l LEFT JOIN course c ON l.course_id=c.pkid ".
				 		"WHERE l.course_id IN (".$str_course_list.")";
				 $lesson_List = $lesson->query($sql);
				 $this->assign('lesson_List',$lesson_List);
				 
				 //获取章节列表
				 if(!empty($lesson_List)){
					 foreach($lesson_List as $k=>$v){
					 	 $IdList[] = $v['pkid'];
					 }
					 $strIdList = implode(',',$IdList);
					 $lesson_video = D("LessonVideo");
					 $sql = "SELECT * FROM lesson_video WHERE lesson_id IN (".$strIdList.") AND video_type<>3";
					 $video_List = $lesson_video->query($sql);
					 $arr_video_list = array();
					 $array_video_type = array(1=>'正课',2=>'练习',3=>'试课');
					 foreach($video_List as $key=>$val){
					 	 $val['type_name'] = $array_video_type[$val['video_type']];
					 	 $arr_video_list[$val['lesson_id']][] = $val;
					 }
					 $this->assign('arr_video_list',$arr_video_list);
				 }
			}
		}
		$this->display('index');
    }
}