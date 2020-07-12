<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>用户注册</title>
	<link rel="stylesheet" href="/Public/Home/style/base.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/global.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/header.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/login.css" type="text/css">
	<link rel="stylesheet" href="/Public/Home/style/footer.css" type="text/css">
</head>
<body>
	<!-- 顶部导航 start -->
		<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w1210 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
					<?php if(empty($_SESSION['user_id'])): ?><li>您好，欢迎来到商城！[<a href="<?php echo U('user/login');?>">登录</a>] [<a href="<?php echo U('user/regist');?>">免费注册</a>] </li>
					<?php else: ?>
					<li>您好 <b style="color: #e1251b;"><?php echo ($_SESSION['user']['username']); ?></b> ，欢迎来到商城！[<a href="<?php echo U('user/logout');?>">退出</a>]</li><?php endif; ?>
					<li class="line">|</li>
					<li>我的订单</li>
					<li class="line">|</li>
					<li>客户服务</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>

	
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>

	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="/Public/Home/images/logo.png" alt="京西商城"></a></h2>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<!-- 登录主体部分start -->
	<div class="login w990 bc mt10 regist">
		<div class="login_hd">
			<h2>用户注册</h2>
			<b></b>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form action="" method="post">
					<ul>
						<li>
							<label for="">用户名：</label>
							<input type="text" class="txt" name="username" />
							<p>3-20位字符，可由中文、字母、数字和下划线组成</p>
						</li>
						<li>
							<label for="">密码：</label>
							<input type="password" class="txt" name="password" id="firstpass"/>
							<p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
						</li>
						<li>
							<label for="">确认密码：</label>
							<input type="password" class="txt" name="password" id="secondpass"/>
							<span id="void" style="color: red;"></span>
						</li>
						<li class="checkcode">
							<label for="">验证码：</label>
							<input type="text"  name="checkcode" />
							<img src="<?php echo U('code');?>" alt="" />
							<span>看不清？<a href="javascript:;" id="flushcode">换一张</a></span>
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				
			</div>
			
			<div class="mobile fl">
				<h3>手机快速注册</h3>			
				<p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
				<p><strong>1069099988</strong></p>
			</div>

		</div>
	</div>
	<!-- 登录主体部分end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<!-- 底部版权 start -->
<div class="footer w1210 bc mt10">
	<p class="links">
		<a href="">关于我们</a> |
		<a href="">联系我们</a> |
		<a href="">人才招聘</a> |
		<a href="">商家入驻</a> |
		<a href="">千寻网</a> |
		<a href="">奢侈品网</a> |
		<a href="">广告服务</a> |
		<a href="">移动终端</a> |
		<a href="">友情链接</a> |
		<a href="">销售联盟</a> |
		<a href="">商城论坛</a>
	</p>
	<p class="copyright">
			© 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
	</p>
	<p class="auth">
		<a href=""><img src="/Public/Home/images/xin.png" alt="" /></a>
		<a href=""><img src="/Public/Home/images/kexin.jpg" alt="" /></a>
		<a href=""><img src="/Public/Home/images/police.jpg" alt="" /></a>
		<a href=""><img src="/Public/Home/images/beian.gif" alt="" /></a>
	</p>
</div>
<!-- 底部版权 end -->
<div style="clear:both;"></div>
	<!-- 底部版权 end -->

</body>
</html>
<script src="/Public/Home/js/jquery-1.8.3.min.js"></script>
<script src="/Public/Home/js/jquery.form.js"></script>
<script src="/Public/Home/layer/layer.js"></script>
<script>
	$('form').submit(function(){
		$(this).ajaxSubmit({
			url:"<?php echo U('regist');?>",  // 表单提交的地址
			type:"post",
			dataType:"json",  // 指定数据交互格式
			success:function(msg){
				if(msg.status==1){
					// 表单提交成功
					alert('注册成功，请登录');
					location.href="<?php echo U('login');?>";
				}else{
					layer.msg(msg.msg);
				}
			}
		});
		// 阻止表单默认提交
		return false;
	})
	$("#flushcode").click(function(){
        var url="<?php echo U('User/code');?>"+"?date="+new Date().getTime();
		$(this).parent().prev().attr('src',url);
	})
	$("#secondpass").blur(function(){
		var firstpass=$("#firstpass").val();
		var secondpass=$(this).val();
		if(firstpass != secondpass){
			$(this).next().html("两次密码不相同");
		}else{
			$(this).next().html("");
		}
	})
</script>