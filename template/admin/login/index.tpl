<{include file="../header.tpl" title="后台登陆"}>
<div class="container">
	<form class="form-signin" method="post" role="form" action="/admin/login/action">
		<h2 class="form-signin-heading">博客后台登陆</h2>
		<input type="text" name="username" class="form-control" placeholder="请输入账号" required="required" autofocus="autofocus" />
		<input type="password" name="password" class="form-control" placeholder="请输入密码" required="required"  />
		<label class="checkbox">
			<input type="checkbox" value="remember-me"> 记住密码
		</label>
		<button class="btn btn-lg btn-primary btn-block" type="submit">登陆</button>
	</form>
</div>
<{include file="../footer.tpl"}>