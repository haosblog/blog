<script type="text/javascript" src="/static/common/js/plugin/jquery.selection.js"></script>
<script type="text/javascript" src="/static/admin/js/editor.js"></script>
<div data-target="#<{$name}>" data-role="editor-toolbar" class="btn-toolbar">
	<div class="btn-group">
		<a title="" data-toggle="dropdown" class="btn dropdown-toggle" data-original-title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
			<li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
			<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
		</ul>
	</div>
	<div class="btn-group">
		<a title="" data-edit="bold" class="btn" data-original-title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
		<a title="" data-edit="italic" class="btn" data-original-title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
		<a title="" data-edit="strikethrough" class="btn" data-original-title="Strikethrough"><i class="icon-strikethrough"></i></a>
		<a title="" data-edit="underline" class="btn" data-original-title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
	</div>
	<div class="btn-group">
		<a title="" data-edit="insertunorderedlist" class="btn" data-original-title="Bullet list"><i class="icon-list-ul"></i></a>
		<a title="" data-edit="insertorderedlist" class="btn" data-original-title="Number list"><i class="icon-list-ol"></i></a>
		<a title="" data-edit="outdent" class="btn" data-original-title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
		<a title="" data-edit="indent" class="btn" data-original-title="Indent (Tab)"><i class="icon-indent-right"></i></a>
	</div>
	<div class="btn-group">
		<a title="" data-popover="#link" class="btn" data-original-title="Hyperlink"><i class="icon-link"></i></a>
		<div data-popover-content="link" style="display: none">
			<div class="input-append">
				<input type="text" data-edit="createLink" placeholder="URL" class="span2">
				<button type="button" class="btn">Add</button>
			</div>
		</div>
		<a title="" data-edit="unlink" class="btn" data-original-title="Remove Hyperlink"><i class="icon-cut"></i></a>

	</div>

	<div class="btn-group">
		<a id="pictureBtn" title="" class="btn" data-original-title="Insert picture (or just drag &amp; drop)"><i class="icon-picture"></i></a>
		<input type="file" data-edit="insertImage" data-target="#pictureBtn" data-role="magic-overlay" style="opacity: 0; position: absolute; top: 0px; left: 0px; width: 41px; height: 30px;">
	</div>
	<div class="btn-group">
		<a title="" data-edit="undo" class="btn" data-original-title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
		<a title="" data-edit="redo" class="btn" data-original-title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
	</div>
	<input type="text" x-webkit-speech="" id="voiceBtn" data-edit="inserttext" style="display: none;">
</div>
<textarea id="<{$name}>" name="<{$name}>" class="form-control" rows="15"><{$content}></textarea>