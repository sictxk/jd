<?php
class NoteModel extends Model {
	protected $tableName = 'note';
	protected $trueTableName = 'note';
	protected $fields = array('pkid', 'content', 'image','ctime', '_pk'=>'pkid', '_autoinc'=>true);
	
	// 自动验证设置
    protected $_validate = array(

        array('content', 'require', '内容必须', Model::MUST_VALIDATE),

    );
	
	// 自动填充设置
    protected $_auto = array(

        array('ctime', 'ctime', self::MODEL_INSERT, 'function')

    );
}
?>