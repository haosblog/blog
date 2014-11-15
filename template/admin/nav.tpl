<nav>
	<ul>
		<li>
			<a class="list-group-item menu-first" href="/admin/">
				后台首页
				<span class="pull-right"><i class="glyphicon glyphicon-chevron-right"></i></span>
			</a>
		</li>
		<li>
			<a id="menu_site" href="#siteMenu" class="list-group-item menu-first collapsed" data-toggle="collapse">
				站点管理
				<span class="pull-right down"><i class="glyphicon glyphicon-chevron-down"></i></span>
				<span class="pull-right up"><i class="glyphicon glyphicon-chevron-up"></i></span>
			</a>
			<ul id="siteMenu" class="nav nav-list collapse menu-second">
				<li><a href="/admin/site/add">添加站点</a></li>
				<li><a href="/admin/site">站点列表</a></li>
				<li><a href="/admin/site/domain">域名列表</a></li>
			</ul>
		</li>
		<li>
			<a id="menu_model" href="#modelMenu" class="list-group-item menu-first collapsed" data-toggle="collapse">
				系统模型管理
				<span class="pull-right down"><i class="glyphicon glyphicon-chevron-down"></i></span>
				<span class="pull-right up"><i class="glyphicon glyphicon-chevron-up"></i></span>
			</a>
			<ul id="modelMenu" class="nav nav-list collapse menu-second">
				<li><a href="/admin/model/">模型列表</a></li>
				<li><a href="/admin/model/import/">导入模型</a></li>
			</ul>
		</li>
		<li>
			<a id="menu_photo" href="#photoMenu" class="list-group-item menu-first collapsed" data-toggle="collapse">
				相片管理
				<span class="pull-right down"><i class="glyphicon glyphicon-chevron-down"></i></span>
				<span class="pull-right up"><i class="glyphicon glyphicon-chevron-up"></i></span>
			</a>
			<ul id="photoMenu" class="nav nav-list collapse menu-second">
				<li><a href="/admin/photo/album">相册管理</a></li>
				<li><a href="/admin/photo/">图片管理</a></li>
				<li><a href="/admin/photo/upload">上传图片</a></li>
			</ul>
		</li>
		<{foreach from=$model item=modelRow}>
			<li>
				<a href="#<{$modelRow.tablename}>" class="list-group-item menu-first collapsed" data-toggle="collapse"><{$modelRow.modname}>管理</a>
				<ul id="<{$modelRow.tablename}>" class="nav nav-list collapse menu-second">
					<li><a href="/admin/model/post?mid=<{$modelRow.mid}>">发表<{$modelRow.modname}></a></li>
					<{if $modelRow.classable eq 1}>
						<li><a href="/admin/category?mid=<{$modelRow.mid}>"><{$modelRow.modname}>分类</a></li>
					<{/if}>
					<li><a href="/admin/model/contentlist?mid=<{$modelRow.mid}>"><{$modelRow.modname}>列表</a></li>
				</ul>
			</li>
		<{/foreach}>
	</ul>
</nav>