<?php
    class CategoryModel extends Model{
		protected $tableName = 'category';
		protected $trueTableName = 'note';
		protected $fields = array('pkid', 'title','ctime', 'status',  '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ���֤����
	    protected $_validate = array(
	        array('title', 'require', '�������', Model::MUST_VALIDATE),
			//array('title', '', '�����Ѿ�����', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        //array('category_id', 'require', '�������', Model::MUST_VALIDATE),
	        //array('context', 'require', '���ݱ���', Model::MUST_VALIDATE)
	    );
		
		// �Զ��������
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );

    }
    
?>    