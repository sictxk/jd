<?php
    class AgencyModel extends Model{
		protected $tableName = 'agency';
		protected $trueTableName = 'agency';
		/*protected $fields = array('pkid','code','pass','category_id','brand_id','title','picture','province_id','city_id','area_id',
                    'address','telephone','operating_hours','main_business','other_info','vouchsafe','long_lat','sign_type', 'ctime',
            		 'status','_pk'=>'pkid', '_autoinc'=>true);*/
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '标题必须', Model::MUST_VALIDATE),
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function'),
	    );
    	
    }
    
?>    