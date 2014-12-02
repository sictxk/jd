<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>交大100后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/list.css" />
	<script src="/Public/Frontend/jQueryAssets/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script type="text/javascript">    
	    function DoEdit(Pkid){
	      var url = "/Backend/LessonVideo/edit/pkid/"+Pkid+'/pno/'+<?php echo ($pNo); ?> ;
	      var lesson_id = $("#lesson_id").val();
	      if(lesson_id!=''){
	      	url +='/lessonId/'+lesson_id;
	      }
    		window.location.href=url;
	    }
	    
	   function SetFree(Pkid){
	      var url = "/Backend/LessonVideo/setFree/pkid/"+Pkid+'/pno/'+<?php echo ($pNo); ?> ;
	      var lesson_id = $("#lesson_id").val();
	      if(lesson_id!=''){
	      	url +='/lessonId/'+lesson_id;
	      }
    		window.location.href=url;
	    }
	    
	    function CancelFree(Pkid){
	      var url = "/Backend/LessonVideo/cancelFree/pkid/"+Pkid+'/pno/'+<?php echo ($pNo); ?> ;
	      var lesson_id = $("#lesson_id").val();
	      if(lesson_id!=''){
	      	url +='/lessonId/'+lesson_id;
	      }
    		window.location.href=url;
	    }
	    
	</script>
</head>

<body>
	<div class="header">
		课程列表
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
			<select id="lesson_id" name="lesson_id" onchange="" ondblclick="" class="" ><option value="" >选择教材</option><?php  foreach($arr_lesson as $key=>$val) { if(!empty($lesson_id) && ($lesson_id == $key || in_array($key,$lesson_id))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
			<input type="submit" value="搜索" />
		</div>
	</form>
	<div class="container">
		<table border="1" cellpadding="5" cellspacing="0" width="100%" class="tableskin">
			<tr>
				<th width="">课程标题</th>
				<th width="150">所属教材</th>
				<th width="100">视频文件</th>
				<th width="150">视频截图</th>
				<th width="80">播放次数</th>
				<th width="100">显示状态</th>
				<th width="150">发布时间</th>
				<th width="100">操作</th>
			</tr>
			
			<?php if(is_array($lesson_video_list)): $i = 0; $__LIST__ = $lesson_video_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td>
					<a href="javascript:DoEdit('<?php echo ($vo["pkid"]); ?>');"><?php echo ($vo["title"]); ?></a>
				</td>
				<td><?php echo ($arr_lesson[$vo['lesson_id']]); ?></td>
				<td><?php if($vo["video_path"] != ''): ?>已上传<?php else: ?>未上传<?php endif; ?></td>
				<td><?php if($vo["screenshot"] != ''): ?><img src="<?php echo ($vo["screenshot"]); ?>" width="80"/><?php else: ?>未上传<?php endif; ?></td>
				<td><?php echo ($vo["play_times"]); ?></td>
				<?php if($vo["status"] == 'Y'): ?><td align="center"><a href="/Backend/LessonVideo/hide/pkid/<?php echo ($vo["pkid"]); ?>"><img src="/Public/Backend/images/public/yes.gif" /></a></td>
				<?php else: ?>
				<td align="center"><a href="/Backend/LessonVideo/show/pkid/<?php echo ($vo["pkid"]); ?>"><img src="/Public/Backend/images/public/no.gif" /></a></td><?php endif; ?>
				<td><?php echo ($vo["ctime"]); ?></td>
				<td align="center">
					<a href="javascript:DoDel('<?php echo ($vo["pkid"]); ?>');">删除</a>
					<?php if($vo["free_play"] == 'N'): ?><a href="javascript:SetFree('<?php echo ($vo["pkid"]); ?>');">设为试看</a>
					<?php else: ?><a href="javascript:CancelFree('<?php echo ($vo["pkid"]); ?>');">取消试看</a><?php endif; ?>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
		<div class="pagenation">
			<?php echo ($page); ?>
		</div>
	</div>
</body>
</html>