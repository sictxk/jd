<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="author" content="交大100,中小学辅导,中高考,中考,高考,升学,家教">
<meta name="description" content="交大100">
<meta name="keywords" content="交大100,网络教育,在线课程,在线课堂,中小学辅导,中高考,中考,高考,升学,家教">
<title>交大100-在线学堂</title>
<link href="/Public/Frontend/jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="/Public/Frontend/jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="/Public/Frontend/jQueryAssets/jquery.ui.tabs.min.css" rel="stylesheet" type="text/css">
<link href="/Public/Frontend/css/generic.css" type="text/css" rel="stylesheet">
<script src="/Public/Frontend/jQueryAssets/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/Public/Frontend/jQueryAssets/jquery-ui-1.9.2.tabs.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/Public/Frontend/script/public.js"></script>
<script type="text/javascript">
$(function() {
	$( ".leveltrylook,.level" ).tabs(); 
});
</script>
</head>

<body>
<div class="wrapper">
    <div class="topwrap">
        <div class="logo"><div class="slogan"></div></div>
        <div class="topo">
            <div class="tel"><span>咨询热线：</span>021-34690659</div>
            <nav>
            <div class="nav">
                <a href="/">首页</a>
                <a href="/Frontend/Learning">学习中心</a>
                <a href="/Frontend/Preview">试看课程</a>
                <a href="/Frontend/Teacher">师资团队</a>
                <a href="/Frontend/Notice">公告通知</a>
                <a href="">交大100</a>
            </div>
            </nav>
        </div>
    </div>
    
  <div class="banner" style="background-image:url(/Public/Frontend/images/banner2.jpg)">
        <div class="loginwrap">
        	<b class="loginbg"></b>
            <?php if($user_info == ''): ?><div class="loginbox">
                <h1>开始学习-登录</h1>
                <form id="LoginForm" name="LoginForm" method="post" action="/Frontend/User/login_verify">
                    <label class="username"><input class="logininput" id="username" name="username" placeholder="账号/手机号/邮箱"></label>
                    <label class="password"><input class="loginpassword" id="password" name="password" type="password"  placeholder="******"></label>
                  <label class="forgetpw"><a>忘记密码？</a></label>
                  <label class="submitlog"><input type="button" value="登 陆" id="LoginBtn"></label>
                </form>
              <a href="/Frontend/User/register" class="registersub">还没有账号，马上注册!</a>
            </div>
            <?php else: ?>
                <div class="loginbox">
                    <h1>开始学习</h1>
                        <label class="username">学生：<?php echo ($user_info["truename"]); ?></label>
                        <label class="password">年级：<?php echo ($user_info["grade_title"]); ?></label>
                    <label class="submitlog"><input type="button" value="学习中心" id="" onclick="javascript:location.href='/Frontend/Learning'"></label>
                </div><?php endif; ?>
        </div>
    </div>
    
  <hr>
    
  <div class="parentsmsgwrap">
    	<div class="left">
            <h1>家长心声</h1>
            <img src="/Public/Frontend/images/parentsmsg.jpg" />
        </div>
        <div class="right">
        	<ul class="msglist">
            	<li>
                	<h2>初三年级 学生 李萌萌 家长 刘舞均   08:25</h2>
                    <div class="content"><b></b>
                    	<span>看着孩子越来越浓厚的学习兴趣，非常欣慰，当初的选择是正确的！</span>
                    </div>
                </li>
            	<li>
                	<h2>初三年级 学生 李萌萌 家长 刘舞均   08:25</h2>
                    <div class="content"><b></b>
                    	<span>看着孩子越来越浓厚的学习兴趣，非常欣慰，当初的选择是正确的！</span>
                    </div>
                </li>
            	<li>
                	<h2>初三年级 学生 李萌萌 家长 刘舞均   08:25</h2>
                    <div class="content"><b></b>
                    	<span>看着孩子越来越浓厚的学习兴趣，非常欣慰，当初的选择是正确的！</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
  <div class="leveltrylook">
      <h1>试看课程</h1>
      <ul class="threelevel">
        <li><a href="#junior">初中</a></li>
        <li><a href="#high">高中</a></li>
        <li><a href="#primary">小学</a></li>
      </ul>

      <div class="level" id="junior">
<ul>
        	<li><a href="#juniorlevel1">初中 一年级</a></li>
            <li><a href="#juniorlevel2">初中 二年级</a></li>
            <li><a href="#juniorlevel3">初中 三年级</a></li>

        </ul>
        <div id="juniorlevel1">
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>一年级 上册</span></a>
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>一年级 上册</span></a>
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>一年级 上册</span></a>
        </div>
        <div id="juniorlevel2">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>二年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>二年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>二年级 上册</span></a>
        </div>
        <div id="juniorlevel3">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>三年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>三年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>三年级 上册</span></a>
        </div>

      </div>
      <div class="level" id="high">
<ul>
        	<li><a href="#highlevel1">高中 一年级</a></li>
            <li><a href="#highlevel2">高中 二年级</a></li>
            <li><a href="#highlevel3">高中 三年级</a></li>

        </ul>
        <div id="highlevel1">
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>一年级 上册</span></a>
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>一年级 上册</span></a>
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>一年级 上册</span></a>
        </div>
        <div id="highlevel2">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>二年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>二年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>二年级 上册</span></a>
        </div>
        <div id="highlevel3">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>三年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>三年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>三年级 上册</span></a>
        </div>
      </div>
      <div class="level" id="primary">
      	<ul>
        	<li><a href="#level1">一年级</a></li>
            <li><a href="#level2">二年级</a></li>
            <li><a href="#level3">三年级</a></li>
            <li><a href="#level4">四年级</a></li>
            <li><a href="#level5">五年级</a></li>
        </ul>
        <div id="level1">
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>一年级 上册</span></a>
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>一年级 上册</span></a>
        	<a href="/Frontend/Play"><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>一年级 上册</span></a>
        </div>
        <div id="level2">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>二年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>二年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>二年级 上册</span></a>
        </div>
        <div id="level3">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>三年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>三年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>三年级 上册</span></a>
        </div>
        <div id="level4">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>四年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>四年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>四年级 上册</span></a>
        </div>
        <div id="level5">
        	<a href=""><img src="/Public/Frontend/images/book_language.jpg"><span>语文</span><span>五年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_math.jpg"><span>语文</span><span>五年级 上册</span></a>
        	<a href=""><img src="/Public/Frontend/images/book_english.jpg"><span>语文</span><span>五年级 上册</span></a>
        </div>
      </div>
  </div>
  <!--footer-->
  <div class="footer">
  	<hr>
  	<div class="location"><h1>交大100中小学在线教育</h1><p>地址：上海市徐汇区虹漕南路215号（上师大科技园）</p></div>
    <div class="contact"><h1>全国统一咨询热线：</h1><p><br/></p><span>021-34690659 / 021-61507222</span></div>
    <div class="logo2"></div>
  </div>
  <!--footer end-->
</div>
</body>
</html>