<?php
    class LessonVideoModel extends Model{
    	
		protected $tableName = 'lesson_video';
		//protected $trueTableName = 'document';
		protected $fields = array('pkid', 'title','lesson_id', 'screenshot', 'video_path','play_times','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '�½ڱ������', Model::MUST_VALIDATE),
	        array('lesson_id', 'require', '�γ̱���', Model::MUST_VALIDATE),
	        array('screenshot', 'require', '��Ƶ��ͼ����', Model::MUST_VALIDATE),
			//array('title', '', '�����Ѿ�����', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),

	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('play_times', '0', self::MODEL_INSERT),
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    