<?php
    class DocumentModel extends Model{
    	
		protected $tableName = 'document';
		//protected $trueTableName = 'document';
		protected $fields = array('pkid', 'title','summary','category_id', 'is_free', 'score', 'path','size','original_name','ext','hits_num','user_id','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '标题必须', Model::MUST_VALIDATE),
	        array('category_id', 'require', '分类必须', Model::MUST_VALIDATE),
			//array('title', '', '标题已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        //array('score', 'require', '链接必须', Model::MUST_VALIDATE),
	        //array('context', 'require', '内容必须', Model::MUST_VALIDATE)
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('hits_num', '0', self::MODEL_INSERT),
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    