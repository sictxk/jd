<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/general.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/login.css" />
</head>

<body>
	<div class="container">
		<h1 class="header">复爵教育后台管理</h1>
		<div class="area">
			<div class="login">
				<div class="app">
					<a href="#"><img id="mac" alt="App Store" src="/Public/Backend/images/public/empty.gif" /></a>
					<a href="#"><img id="andriod" alt="Andriod" src="/Public/Backend/images/public/empty.gif" /></a>
				</div>
				<form method="POST" name="form" id="form" action="/Backend/Index/login">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="80" class="large">账户名</td>
							<td><input type="text" class="txt" name="account" maxlength="16" value="<?php echo ($arr_form["account"]); ?>"/></td>
						</tr>
						<tr>
							<td class="large">密码</td>
							<td><input type="password" class="txt" name="password" maxlength="16" value=""/></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" value="登陆管理面板" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<div class="plus">
			<ul>
				<li>
					<a href="#">
						<img id="rss" alt="" src="/Public/Backend/images/public/empty.gif" />
					</a>
				</li>
				<li>
					<a href="#">
						<img id="weibo" alt="" src="/Public/Backend/images/public/empty.gif" />
					</a>
				</li>
				<li>
					<a href="#">
						<img id="info" alt="" src="/Public/Backend/images/public/empty.gif" />
					</a>
				</li>
			</ul>
		</div>
		<div class="footer">
			&copy; Copyright 2014. All Rights Reserved.
		</div>
	</div>
</body>
</html>