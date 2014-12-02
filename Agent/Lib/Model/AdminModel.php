<?php
    class AdminModel extends Model{
    	
		protected $tableName = 'admin';
		//protected $trueTableName = 'admin';
		protected $fields = array('pkid', 'account', 'password', 'email','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('account', 'require', '�˺ű���', Model::MUST_VALIDATE),
			//array('account', '', '�˺��Ѿ�����', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('password', 'require', '���ӱ���', Model::MUST_VALIDATE),
	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    