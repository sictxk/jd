<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>交大100后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/editor.css" />
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-core-1.4.5.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-more-1.4.0.1.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/public.js"></script>
    <script type="text/javascript" src="/Public/Backend/scripts/jquery-1.4.2.js"></script>
    <script type="text/javascript">var $$=jQuery.noConflict();</script>
    <script type="text/javascript" src="/Public/Backend/scripts/jq_lesson.js"></script>
	<script type="text/javascript">
		window.addEvent('domready', function(){
			initTab(0);
			checkLessonVideo();
		});
	</script>
	
</head>

<body>
	<div class="header">
		新增课程
	</div>
	
	<div class="container">
		<form id="MyForm1" name="MyForm1" method="post" action="/Backend/LessonVideo/save" enctype="multipart/form-data">
			<ul id="tabmenu">
				<li><a href="javascript:void(0);">基本信息</a></li>
			</ul>
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
						<tr>
							<td align="right" width="100"><span class="need">教材章节：</span></td>
							<td>
							<select id="lesson_id" name="lesson_id" onchange="" ondblclick="" class="" ><option value="" >选择教材</option><?php  foreach($arr_lesson as $key=>$val) { if(!empty($lesson_id) && ($lesson_id == $key || in_array($key,$lesson_id))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
							<span id="chapter_panel"></span>
							</td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">课程标题：</span></td>
							<td><input name="title" type="text" id="title"  class="long"  value="<?php echo ($arr_form["title"]); ?>"/></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">视频截图：</span></td>
							<td><input name="screenshot" type="file" id="screenshot"  class="long" value=""/>(支持jpg格式)</td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">视频地址：</span></td>
							<td><input name="video_path" type="text" id="video_path"  class="long" value="<?php echo ($arr_form["video_path"]); ?>"/>(请FTP上传到/Public/Upload/Lesson/Video/下后填写完整路径)</td>
						</tr>
                        <tr>
                            <td align="right" width="100"><span class="need">视频类型：</span></td>
                            <td>
                                <input type="radio" name="video_type[]" value="1">正课<input type="radio" name="video_type[]" value="2">练习</td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">教材讲义：</span></td>
                            <td><input name="document" type="file" id="document"  class="long" value=""/></td>
                        </tr>
						<tr>
							<td align="right" width="100"><span class="need">对外状态：</span></td>
							<td>
							<input type="radio" name="status[]" value="Y">显示<input type="radio" name="status[]" value="N">隐藏</td>
						</tr>
					</table>
		    	</div>
				
			</div>
				<div>
				<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
					<tr>
						<td width="100" height="40">&nbsp;</td>
						<td>
							<input type="button" id="SubmitBtn" name="SubmitBtn" value="提交" />
							<input type="button" id="BackBtn" name="BackBtn" value="返回" onclick="javascript:history.back();"/>
						</td>
					</tr>
				</table>
				</div>
		</form>
	</div>
</body>
</html>