<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>静安商务委后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/editor.css" />
	<!--<link href="/Public/Backend/scripts/DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">-->
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-core-1.4.5.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-more-1.4.0.1.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/public.js?v=20140215"></script>
	<!--<script type="text/javascript" src="/Public/Backend/scripts/DatePicker/WdatePicker.js"></script>
	<script type="text/javascript" src="/Public/Backend/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/Public/Backend/ckeditor/ckfinder/ckfinder.js"></script>-->

	<script type="text/javascript">
		window.addEvent('domready', function(){
			initTab(0);
			checkNotice();
		});
	</script>

</head>

<body>
	<div class="header">
		新增通知
	</div>
	
	<div class="container">
		<form id="MyForm1" name="MyForm1" method="post" action="/Backend/AgencyNotice/save">
			<ul id="tabmenu">
				<li><a href="javascript:void(0);">基本信息</a></li>

				<!--<li><a href="javascript:void(0);">通知正文</a></li>-->
			</ul>
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
						<tr>
							<td align="right" width="100"><span class="need">通知标题：</span></td>
							<td><textarea name="title" id="title" cols="50" rows="1"/><?php echo ($arr_form["title"]); ?></textarea></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">通知内容：</span></td>
							<td><textarea id="content" name="content" rows="12" cols="50" class="long"><?php echo ($arr_form["content"]); ?></textarea></td>
						</tr>
						<!--<tr>
							<td align="right" width="100"><span class="need">通知范围：</span></td>
							<td></td>
						</tr>-->						
						<!--<tr>
							<td align="right" width="100"><span class="need">结束日期：</span></td>
							<td><input name="exdate" type="text" id="exdate" maxlength="50" class="long" onclick="WdatePicker()" value="<?php echo ($arr_form["exdate"]); ?>"/></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">对外状态：</span></td>
							<td>    
							</td>
						</tr>-->
					</table>
		    	</div>
				<!--<div class="tabitem">
				  <textarea id="context" name="context" rows="12" cols="20" class="long"><?php echo ($arr_form["context"]); ?></textarea>
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
	var editor = CKEDITOR.replace('context',{
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