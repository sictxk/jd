<?php
header("Content-type: text/html; charset=utf-8");
class PlayAction extends Action {
    public function index(){
    	if(empty($_SESSION['user_info'])){
    		redirect('/');
    	}
    	//获取当前视频
    	 $videoId = $this->_param('videoId'); 
	 $lesson_video = D("LessonVideo");
	 $sql = "SELECT lv.*,l.title as lesson_title,g.title as grade_title FROM lesson_video lv LEFT JOIN lesson l ON lv.lesson_id=l.pkid ".
	 		"LEFT JOIN grade g ON l.grade_id=g.pkid  WHERE lv.pkid =".$videoId;
	 $dataVideo = $lesson_video->query($sql);
	 $this->assign('dataVideo',$dataVideo[0]);
	 $this->assign('video_path',$dataVideo[0]['video_path']);
	 
	 
	 //获取课程章节列表
	 $sql = "SELECT * FROM lesson_video  ".
	 		"WHERE lesson_id = ".$dataVideo[0]['lesson_id'];
	 $lesson_List = $lesson_video->query($sql);
	 
	 $array_video_type = array(1=>'正课',2=>'练习',3=>'试课');
	 foreach($lesson_List as $key=>$val){
	 	 $lesson_List[$key]['type_name'] = $array_video_type[$val['video_type']];
	 }
	 $this->assign('lesson_List',$lesson_List);
        $this->display('index');
    }
}