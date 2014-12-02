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
			checkUser();
		});
	</script>

</head>

<body>
	<div class="header">
		录入学员信息
	</div>
	
	<div class="container">
		<form id="MyForm1" name="MyForm1" method="post" action="/Backend/User/save">
			<ul id="tabmenu">
				<li><a href="javascript:void(0);">基本信息</a></li>

				<!--<li><a href="javascript:void(0);">快讯正文</a></li>-->
			</ul>
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
                        <tr>
                            <td align="right" width="100"><span class="need">姓名：</span></td>
                            <td><input id="truename" name="truename" maxlength="100" class="long"  value="<?php echo ($arr_form["truename"]); ?>"/></td>
                        </tr>
						<tr>
							<td align="right" width="100"><span class="need">登陆账号：</span></td>
							<td><input name="account" type="text" id="account" maxlength="100" class="long"  value="<?php echo ($arr_form["account"]); ?>"/></td>
						</tr>
                        <tr>
                            <td align="right" width="100"><span class="need">登陆密码：</span></td>
                            <td><input name="password" type="text" id="password" maxlength="100" class="long"  value="<?php echo ($arr_form["password"]); ?>"/></td>
                        </tr>
						<tr>
                        <td align="right" width="100"><span class="need">邮箱：</span></td>
                        <td><input id="email" name="email"  maxlength="100" class="long"  value="<?php echo ($arr_form["email"]); ?>"/></td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">手机：</span></td>
                            <td><input id="mobile" name="mobile" maxlength="100" class="long"  value="<?php echo ($arr_form["mobile"]); ?>"/></td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">家长：</span></td>
                            <td><input id="parents" name="parents" maxlength="100" class="long"  value="<?php echo ($arr_form["parents"]); ?>"/></td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">学校：</span></td>
                            <td>
                            <input id="school" name="school" maxlength="100" class="long"  value="<?php echo ($arr_form["school"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">年级：</span></td>
                            <td>
                                <select id="grade_id" name="grade_id" onchange="" ondblclick="" class="" ><option value="" >选择年级</option><?php  foreach($grade_id as $key=>$val) { if(!empty($grade_id) && ($grade_id == $key || in_array($key,$grade_id))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
                            </td>
                        </tr>
                        <tr>
                                  <td align="right" width="100"><span class="need">性别：</span></td>
                                  <td><input type="radio" name="gender_status[]" value="M">男<input type="radio" name="gender_status[]" value="F">女</td>
                        </tr>
						<!--<tr>
							<td align="right" width="100"><span class="need">账号状态：</span></td>
							<td><input type="radio" name="status[]" value="Y">显示<input type="radio" name="status[]" value="N">隐藏</td>
						</tr>-->
					</table>
		    	</div>
				<!--<div class="tabitem">
				  <input id="description" name="description" rows="12" cols="20" class="long"><?php echo ($arr_form["description"]); ?>"/>
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