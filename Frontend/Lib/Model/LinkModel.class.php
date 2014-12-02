<?php
    class LinkModel extends Model{
    	
		protected $tableName = 'link';
		//protected $trueTableName = 'link';
		protected $fields = array('pkid', 'title', 'url', 'thumb','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '�������', Model::MUST_VALIDATE),
			//array('title', '', '�����Ѿ�����', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('url', 'require', '���ӱ���', Model::MUST_VALIDATE),
	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    