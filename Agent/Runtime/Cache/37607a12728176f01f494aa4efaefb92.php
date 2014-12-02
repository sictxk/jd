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
    <script type="text/javascript" src="/Public/Backend/scripts/jquery-1.4.2.js"></script>
    <script type="text/javascript">var $$=jQuery.noConflict();</script>
    <script type="text/javascript" src="/Public/Backend/scripts/jq_agency.js"></script>
	<script type="text/javascript">
		window.addEvent('domready', function(){
			initTab(0);
			checkLesson();
		});
	</script>
</head>

<body>
	<div class="header">
		课程编辑&gt;&gt;<?php echo ($arr_form["title"]); ?>
	</div>
	<div class="container">
		<form id="MyForm1" name="MyForm1" method="post" action="/Backend/Lesson/renew" enctype="multipart/form-data">
			<input name="pkid" type="hidden" value="<?php echo ($arr_form["pkid"]); ?>"/>
			<ul id="tabmenu">
				<li><a href="javascript:void(0);">基本信息</a></li>
			</ul>
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
                        <tr>
                            <td align="right" width="100"><span class="need">所属科目：</span></td>
                            <td><select id="grade_id" name="grade_id" onchange="" ondblclick="" class="" ><option value="" >选择年级</option><?php  foreach($grade_id as $key=>$val) { if(!empty($grade_value) && ($grade_value == $key || in_array($key,$grade_value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
                                <span id="course_panel"><select id="course_id" name="course_id" onchange="" ondblclick="" class="" ><option value="" >选择科目</option><?php  foreach($course_list as $key=>$val) { if(!empty($course_value) && ($course_value == $key || in_array($key,$course_value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select></span>
                            </td>
                        </tr>
						<tr>
							<td align="right" width="100"><span class="need">课程标题：</span></td>
							<td><input name="title" type="text" id="title" maxlength="100" class="long"  value="<?php echo ($arr_form["title"]); ?>"/></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">课程讲师：</span></td>
							<td><input name="lectuer" type="text" id="lectuer" maxlength="100" class="long"  value="<?php echo ($arr_form["lectuer"]); ?>"/></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">讲师自述：</span></td>
							<td><textarea name="lectuer_intro" id="lectuer_intro" cols="10" rows="10" class="long"><?php echo ($arr_form["lectuer_intro"]); ?></textarea></td>
						</tr>
                        <tr>
                            <td align="right" width="100"><span class="need">教材封面：</span></td>
                            <td><input name="book_cover" type="file" id="book_cover" maxlength="100" class="long" value=""/>(支持jpg格式)</td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">课程费用：</span></td>
                            <td><input name="price" type="text" id="price" maxlength="100" class="long"  value="<?php echo ($arr_form["price"]); ?>"/>元</td>
                        </tr>
						<tr>
							<td align="right" width="100"><span class="need">对外状态：</span></td>
							<td>
							<input type="radio" checked="checked" name="status[]" value="Y">显示<input type="radio" name="status[]" value="N">隐藏</td>
						</tr>
					</table>
		    	</div>				
			</div>
				<div>
				<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
					<tr>
						<td width="100" height="40">&nbsp;</td>
						<td>
							<input type="submit" id="SubmitBtn" name="SubmitBtn" value="提交" />
							
						</td>
					</tr>
				</table>
				</div>
		</form>
	</div>
</body>
</html>