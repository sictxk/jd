<?php
    class LessonModel extends Model{
    	
		protected $tableName = 'lesson';
		//protected $trueTableName = 'document';
		protected $fields = array('pkid', 'title','lectuer', 'lectuer_intro','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '�������', Model::MUST_VALIDATE),
	        array('lectuer', 'require', '��ʦ����', Model::MUST_VALIDATE),
	        array('lectuer_intro', 'require', '��ʦ��������', Model::MUST_VALIDATE),
			//array('title', '', '�����Ѿ�����', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        //array('score', 'require', '���ӱ���', Model::MUST_VALIDATE),
	        //array('context', 'require', '���ݱ���', Model::MUST_VALIDATE)
	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    