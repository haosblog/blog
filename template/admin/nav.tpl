<nav>
	<ul>
		<li>
			<a class="list-group-item menu-first" href="/admin/">
				后台首页
				<span class="pull-right"><i class="glyphicon glyphicon-chevron-right"></i></span>
			</a>
		</li>
		<{foreach from=$menu item=menuItem}>
			<li>
				<a id="menu_<{$menuItem.active}>" href="#<{$menuItem.active}>Menu" class="list-group-item menu-first collapsed menu_button<{if $nav eq $menuItem.active}> on<{/if}>" data-toggle="collapse">
					<{$menuItem.title}>
					<span class="pull-right down"><i class="glyphicon glyphicon-chevron-down"></i></span>
					<span class="pull-right up"><i class="glyphicon glyphicon-chevron-up"></i></span>
				</a>
				<ul id="<{$menuItem.active}>Menu" class="nav nav-list collapse menu-second">
					<{foreach from=$menuItem.sub item=subItem}>
						<li><a href="/admin/<{$subItem.link}>"><{$subItem.title}></a></li>
					<{/foreach}>
				</ul>
			</li>
		<{/foreach}>
		<{foreach from=$model item=modelRow}>
			<li>
				<a id="menu_<{$modelRow.tablename}>" href="#<{$modelRow.tablename}>" class="list-group-item menu-first collapsed menu_button" data-toggle="collapse"><{$modelRow.modname}>管理</a>
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