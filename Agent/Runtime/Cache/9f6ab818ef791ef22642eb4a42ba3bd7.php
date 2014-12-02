<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>交大100代理商平台</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/list.css" />
</head>

<body>
	<div class="header">
		基本信息
	</div>
	<div class="container">
		<form id="MyForm" method="post" action="./?c=User_update_info">
			<input name="up_type" type="hidden" value="detail">
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
						<tr>
							<td align="right" width="100"><span class="need">登录名：</span></td>
							<td colspan="3"><input name="title" type="text" class="long" id="Title" value="<?php echo ($arr_form["login_name"]); ?>" maxlength="20" disabled="true"　readOnly="true" /></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">电子邮箱：</span></td>
							<td colspan="3"><input name="address" type="text" class="long" id="email" value="<?php echo ($arr_form["email"]); ?>" disabled="true"　readOnly="true" /></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">移动电话：</span></td>
							<td colspan="3"><input name="address" type="text" class="tel" id="mobile" value="<?php echo ($arr_form["mobile"]); ?>" disabled="true"　readOnly="true" /></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">联系电话：</span></td>
                            <td><input name="phone" type="text" class="tel" id="phone" value="<?php echo ($arr_form["phone"]); ?>" maxlength="20" disabled="true"　readOnly="true" /></td>
						</tr>
						<tr>
							<td align="right" width="100"><span class="need">联系人：</span></td>
							<td colspan="3"><input name="contact" type="text" class="tel" id="contact" value="<?php echo ($arr_form["contact"]); ?>" disabled="true"　readOnly="true" /></td>
						</tr>
						<tr>
							<td align="right">平台认证：</td>
							<td colspan="3">
								<label for="certified"><input type="checkbox" name="certified" id="certified" value="1" checked="checked" disabled="true"　readOnly="true" />加盟认证</label>（已认证，如需修改信息，请联系管理员。）
                            </td>
						</tr>
					</table>

		    </div>
            </div>
			<div>
				<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
					<tr>
						<td width="100" height="40">&nbsp;</td>
						<td>
							<input type="submit" id="SubmitBtn" value="提交" />
							<input type="reset" value="重置" />
						</td>
					</tr>
				</table>
			</div>
		</form>
	</div>
</body>
</html>