<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/list.css" />
	<script type="text/javascript">    
	    function DoDel(Pkid){
	    	var msg='确定要删除章节吗?';
	    	if(confirm(msg)){
	    		window.location.href="/Backend/LessonVideo/remove/pkid/"+Pkid ;
	    	}
	    }
	    function DoEdit(Pkid){
    		window.location.href="/Backend/LessonVideo/edit/pkid/"+Pkid ;
	    }
	</script>
</head>

<body>
	<div class="header">
		章节列表
	</div>
	<form action="/Backend/LessonVideo/index" method="post" class="formskin">
		<input name="mode" type="hidden" value="search">
		<div>
			<label>
				标题关键字
				<input type="text" name="title" maxlength="50" value="<?php echo ($map["title"]); ?>"/>
			</label>
			
			<select id="" name="status" onchange="" ondblclick="" class="" ><option value="" >选择状态</option><?php  foreach($status as $key=>$val) { if(!empty($value) && ($value == $key || in_array($key,$value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
			<select id="" name="video_type" onchange="" ondblclick="" class="" ><option value="" >选择分类</option><?php  foreach($video_type as $key=>$val) { if(!empty($type_value) && ($type_value == $key || in_array($key,$type_value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
			<input type="submit" value="搜索" />
		</div>
	</form>
	<div class="container">
		<table border="1" cellpadding="5" cellspacing="0" width="100%" class="tableskin">
			<tr>
				<th width="">章节标题</th>
				<th width="150">所属课程</th>
				<th width="300">视频文件</th>
				<th width="100">视频截图</th>
				<th width="80">播放次数</th>
				<th width="100">显示状态</th>
				<th width="150">发布时间</th>
				<th width="50">操作</th>
			</tr>
			
			<?php if(is_array($lesson_video_list)): $i = 0; $__LIST__ = $lesson_video_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td>
					<a href="javascript:DoEdit('<?php echo ($vo["pkid"]); ?>');"><?php echo ($vo["title"]); ?></a>
				</td>
				<td><?php echo ($arr_lesson[$vo['lesson_id']]); ?></td>
				<td><?php if($vo["video_path"] != ''): echo ($vo["video_path"]); else: ?>未上传<?php endif; ?></td>
				<td><?php if($vo["screenshot"] != ''): ?><img src="<?php echo ($vo["screenshot"]); ?>" width="80"/><?php else: ?>未上传<?php endif; ?></td>
				<td><?php echo ($vo["play_times"]); ?></td>
				<?php if($vo["status"] == 'Y'): ?><td align="center"><a href="/Backend/LessonVideo/hide/pkid/<?php echo ($vo["pkid"]); ?>"><img src="/Public/Backend/images/public/yes.gif" /></a></td>
				<?php else: ?>
				<td align="center"><a href="/Backend/LessonVideo/show/pkid/<?php echo ($vo["pkid"]); ?>"><img src="/Public/Backend/images/public/no.gif" /></a></td><?php endif; ?>
				<td><?php echo ($vo["ctime"]); ?></td>
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