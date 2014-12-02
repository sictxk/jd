<?php
    class AgencyCourseModel extends Model{
		protected $tableName = 'agency_course';
		protected $trueTableName = 'agency_course';
		protected $fields = array('pkid', 'title', 'category_id','second_id','third_id','create_time', 'status',  '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '���Ʊ���', Model::MUST_VALIDATE),
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