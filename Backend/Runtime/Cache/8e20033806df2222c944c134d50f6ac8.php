<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/list.css" />
	<script type="text/javascript">    
	    function DoDel(Pkid){
	    	var msg='确定要删除科目吗?';
	    	if(confirm(msg)){
	    		window.location.href="/Backend/Course/remove/pkid/"+Pkid ;
	    	}
	    }
	    function DoEdit(Pkid){
    		window.location.href="/Backend/Course/edit/pkid/"+Pkid ;
	    }
	</script>
</head>

<body>
	<div class="header">
		科目列表
	</div>
	<form action="/Backend/Course/index" method="post" class="formskin">
		<input name="mode" type="hidden" value="search">
		<div>
			<label>
				名称关键字
				<input type="text" name="title" maxlength="50" value="<?php echo ($map["title"]); ?>"/>
			</label>
			
			<select id="" name="grade_id" onchange="" ondblclick="" class="" ><option value="" >选择年级</option><?php  foreach($grade_id as $key=>$val) { if(!empty($grade_value) && ($grade_value == $key || in_array($key,$grade_value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
            <select id="" name="status" onchange="" ondblclick="" class="" ><option value="" >选择状态</option><?php  foreach($status as $key=>$val) { if(!empty($value) && ($value == $key || in_array($key,$value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
			<input type="submit" value="搜索" />
		</div>
	</form>
	<div class="container">
		<table border="1" cellpadding="5" cellspacing="0" width="100%" class="tableskin">
			<tr>
				<th width="250">科目名称</th>
				<th width="100">所属年级</th>
				<th width="50">显示状态</th>
				<th width="200">添加时间</th>
				<th width="100">操作</th>
			</tr>
			

			<?php if(is_array($course_list)): $i = 0; $__LIST__ = $course_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td>
					<a href="javascript:DoEdit('<?php echo ($vo["pkid"]); ?>');"><?php echo ($vo["title"]); ?></a>
				</td>
				<td><?php echo ($vo["grade"]); ?></td>
				<?php if($vo["status"] == 'Y'): ?><td align="center"><a href="/Backend/Course/hide/pkid/<?php echo ($vo["pkid"]); ?>"><img src="/Public/Backend/images/public/yes.gif" /></a></td>
				<?php else: ?>
				<td align="center"><a href="/Backend/Course/show/pkid/<?php echo ($vo["pkid"]); ?>"><img src="/Public/Backend/images/public/no.gif" /></a></td><?php endif; ?>
				<td><?php echo ($vo["create_time"]); ?></td>
				<td align="center">
					<a href="javascript:DoDel('<?php echo ($vo["pkid"]); ?>');">删除</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
		<div class="pagenation">
			<?php echo ($page); ?>
		</div>
	</div>
</body>
</html>