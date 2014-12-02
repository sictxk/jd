<?php
    class PaperModel extends Model{
    	
		protected $tableName = 'paper';
		protected $fields = array('pkid', 'title','grade_id', 'course_id', 'term', 'path','size','hits_num','source','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '�������', Model::MUST_VALIDATE),
	        array('grade_id', 'require', '�������', Model::MUST_VALIDATE),
	        array('course_id', 'require', '��Ŀ����', Model::MUST_VALIDATE),

	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('hits_num', '0', self::MODEL_INSERT),
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    