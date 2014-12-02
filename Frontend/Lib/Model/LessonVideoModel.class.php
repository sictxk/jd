<?php
    class LessonVideoModel extends Model{
    	
		protected $tableName = 'lesson_video';
		//protected $trueTableName = 'document';
		protected $fields = array('pkid', 'title','lesson_id', 'screenshot', 'video_path','play_times','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '章节标题必须', Model::MUST_VALIDATE),
	        array('lesson_id', 'require', '课程必须', Model::MUST_VALIDATE),
	        array('screenshot', 'require', '视频截图必须', Model::MUST_VALIDATE),
			//array('title', '', '标题已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),

	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('play_times', '0', self::MODEL_INSERT),
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    