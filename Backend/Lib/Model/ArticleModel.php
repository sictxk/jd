<?php
    class ArticleModel extends Model{
		protected $tableName = 'article';
		protected $trueTableName = 'article';
		protected $fields = array('pkid', 'title','author','source','picture', 'intro', 'context','type','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '标题必须', Model::MUST_VALIDATE),
			//array('title', '', '标题已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('context', 'require', '内容必须', Model::MUST_VALIDATE),


	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function'),

	    );
    	
    }
    
?>    