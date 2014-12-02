<?php
    class AgencyCategoryModel extends Model{
		protected $tableName = 'agency_category';
		protected $trueTableName = 'note';
		protected $fields = array('pkid', 'title', 'parent_id','level','ctime', 'status',  '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '���Ʊ���', Model::MUST_VALIDATE),
			//array('title', '', '�����Ѿ�����', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('parent_id', 'require', '�����������', Model::MUST_VALIDATE),
	        //array('context', 'require', '���ݱ���', Model::MUST_VALIDATE)
	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );

    }
    
?>    