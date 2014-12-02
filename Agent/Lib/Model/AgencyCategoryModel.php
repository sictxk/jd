<?php
    class AgencyCategoryModel extends Model{
		protected $tableName = 'agency_category';
		protected $trueTableName = 'note';
		protected $fields = array('pkid', 'title', 'parent_id','level','ctime', 'status',  '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '名称必须', Model::MUST_VALIDATE),
			//array('title', '', '名称已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('parent_id', 'require', '父级分类必须', Model::MUST_VALIDATE),
	        //array('context', 'require', '内容必须', Model::MUST_VALIDATE)
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );

    }
    
?>    