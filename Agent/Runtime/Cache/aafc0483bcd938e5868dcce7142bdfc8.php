<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>交大100代理商平台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Agent/css/general.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Agent/css/frameset.css" />
	<script type="text/javascript" src="/Public/Agent/scripts/mootools-core-1.4.5.js"></script>
	<script type="text/javascript" src="/Public/Agent/scripts/mootools-more-1.4.0.1.js"></script>
	<script type="text/javascript">
		window.addEvent('domready', function(){
			new Fx.Accordion($('accordion'), '#accordion h2', '#accordion div', {
				onActive: function(toggler, element){
					toggler.setStyle('background-image', 'url(/Public/Agent/images/menu/minus.gif)');
				},
				onBackground: function(toggler, element){
					toggler.setStyle('background-image', 'url(/Public/Agent/images/menu/plus.gif)');
				}
			});
		});
	</script>
</head>

<body>
<div class="frame" id="topframe">
	<div>
		<span>管理员</span>
		<label>[<?php echo ($account); ?>]</label>
		<span><a href="/Agent/Index/logout" target="_top">[退出]</a></span>
	</div>
</div>
<div class="frame" id="topframeshadow"></div>
<div class="frame" id="leftframeshadow"></div>
<div class="frame" id="leftframe">
	<div id="accordion">

		<h2>代理商管理</h2>
		<div>
			<p><a href="/Agent/Agent/index" target="main">代理商列表</a></p>
			<p><a href="/Agent/Agent/add" target="main">新增代理商</a></p>
            <p><a href="/Agent/ReturnFlow/add" target="main">返利流水</a></p>
		</div>
		<h2>订单管理</h2>
		<div>
			<p><a href="/Agent/AgentOrder/index" target="main">订单列表</a></p>
			<p><a href="/Agent/AgentOrder/add" target="main">添加订单</a></p>
		</div>
        <h2>收款管理</h2>
        <div>
            <p><a href="/Agent/BankInfo/index" target="main">银行账户列表</a></p>
            <p><a href="/Agent/BankInfo/add" target="main">添加银行账户</a></p>
            <p><a href="/Agent/Receipt/add" target="main">添加收款记录</a></p>
            <p><a href="/Agent/Receipt/index" target="main">收款记录列表</a></p>
        </div>
		<h2>卡号管理</h2>
		<div>
			<p><a href="/Agent/Card/index" target="main">卡号列表</a></p>
			<p><a href="/Agent/Card/doAssign" target="main">卡号分配</a></p>
            <p><a href="/Agent/CardAssign/index" target="main">分配记录</a></p>
		</div>
	</div>
</div>

<div class="frame" id="mainframe">
	<iframe id="main" name="main" src="<?php echo ($inner_url); ?>" scrolling="auto" frameborder="0"></iframe>
</div>

<div class="frame" id="bottomframe">
	JiaoDa100 Education Management System v1.0b.
	&copy; 2014. 交大100教育 All Rights Reserved.
</div>
</body>
</html>