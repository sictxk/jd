<?php
    class CourseModel extends Model{
    	
		protected $tableName = 'course';
		protected $trueTableName = 'course';
		protected $fields = array('course_id',  'title', 'category_id','status', 'create_time',   '_pk'=>'course_id', '_autoinc'=>true);
		
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '�������', Model::MUST_VALIDATE),
			//array('title', '', '�����Ѿ�����', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('category_id', 'require', '�������', Model::MUST_VALIDATE),
	        //array('context', 'require', '���ݱ���', Model::MUST_VALIDATE)
	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('create_time', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    