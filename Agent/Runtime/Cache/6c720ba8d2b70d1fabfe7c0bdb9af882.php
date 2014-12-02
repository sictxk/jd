<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/editor.css" />
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-core-1.4.5.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-more-1.4.0.1.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/public.js"></script>

	<script type="text/javascript">
		window.addEvent('domready', function(){
			initTab(0);
			checkCourse();
		});
	</script>

</head>

<body>
	<div class="header">
		新增科目
	</div>
	
	<div class="container">
		<form id="MyForm1" name="MyForm1" method="post" action="/Backend/Course/save" enctype="multipart/form-data">
			<ul id="tabmenu">
				<li><a href="javascript:void(0);">基本信息</a></li>

				<!--<li><a href="javascript:void(0);">科目正文</a></li>-->
			</ul>
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
						<tr>
							<td align="right" width="100"><span class="need">科目名称：</span></td>
							<td><input name="title" type="text" id="title" maxlength="100" class="long"  value="<?php echo ($arr_form["title"]); ?>"/></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">所属年级：</span></td>
							<td><select id="grade_id" name="grade_id" onchange="" ondblclick="" class="" ><option value="" >选择年级</option><?php  foreach($grade_id as $key=>$val) { if(!empty($grade_id) && ($grade_id == $key || in_array($key,$grade_id))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">对外状态：</span></td>
							<td>    
							<input type="radio" name="status[]" value="Y">显示<input type="radio" name="status[]" value="N">隐藏</td>
						</tr>
					</table>
		    	</div>
				<!--<div class="tabitem">
				  <textarea id="description" name="description" rows="12" cols="20" class="long"><?php echo ($arr_form["description"]); ?></textarea>
				</div>-->

				
			</div>
				<div>
				<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
					<tr>
						<td width="100" height="40">&nbsp;</td>
						<td>
							<input type="button" id="SubmitBtn" name="SubmitBtn" value="提交" />
							
						</td>
					</tr>
				</table>
				</div>
		</form>
	</div>
</body>
</html>
<script type="text/javascript"> 
	var editor = CKEDITOR.replace('description',{
		filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
		filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
		filebrowserUploadUrl :
		'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		filebrowserImageUploadUrl :
		'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		filebrowserFlashUploadUrl :
		'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	});
</script>