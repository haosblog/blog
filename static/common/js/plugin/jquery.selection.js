/**
 * @author 愚人码头
 * 文章地址：http://www.css88.com/archives/2316
 * 演示地址：http://www.css88.com/demo/edit-box/index1.html
 * 下载地址：http://www.css88.com/demo/edit-box/selection.rar
 */
(function($) {
	$.fn.selection = function() {
		var s, e, range, stored_range;
		var container = this[0];
		if (this[0].selectionStart == undefined) {
			var selection = document.selection;
			if (this[0].tagName.toLowerCase() != "textarea") {
				var val = this.val();
				range = selection.createRange().duplicate();
				range.moveEnd("character", val.length);
				s = (range.text == "" ? val.length : val.lastIndexOf(range.text));
				range = selection.createRange().duplicate();
				range.moveStart("character", -val.length);
				e = range.text.length;
			} else {
				range = selection.createRange(),
						stored_range = range.duplicate();
				stored_range.moveToElementText(this[0]);
				stored_range.setEndPoint('EndToEnd', range);
				s = stored_range.text.length - range.text.length;
				e = s + range.text.length;
			}
		} else {
			s = this[0].selectionStart, e = this[0].selectionEnd;
		}
		var te = this[0].value.substring(s, e);
		return {
			start: s,
			end: e,
			text: te,
			replace : function(text){
				container.value = container.value.substr(0, this.start) + text + container.value.substr(this.end);
				container.selectionEnd = this.start + text.length;
				if(this.start !== this.end){
					container.selectionStart = this.start;
				}
				container.focus();
			}
		}
	};

	$.fn.selectionLine = function(){

		this.checkFirst = function(start){
			return content.substr(start - 1, 1) === "\n";
		};

		this.checkEnd = function(end){
			return content.substr(end, 1) === "\n";
		};

		var container = this;
		var $container = $(container);
		var selection = $container.selection();
		var content = $container.val();
		var start = selection.start;
		var end = selection.end;
		var newStart, newEnd, newText;

		if(start === 0 || this.checkFirst(start)){
			newStart = start;
		} else {
			for(var i = start; i >= 0; i--){
				if(i === 0 || this.checkFirst(i)){
					newStart = i;
					break;
				}
			}
		}

		if(end === content.length || this.checkEnd()){
			newEnd = end;
		} else {
			for(var i = end; i <= content.length; i++){
				if(i === content.length || this.checkEnd(i)){
					newEnd = i;
					break;
				}
			}
		}

		newText = $(this).val().substring(newStart, newEnd);

		return {
			start: newStart,
			end: newEnd,
			text: newText,
			selection : selection,
			replace : function(pre, type, allowMuti){
				if(typeof type === "undefined"){
					type = 1;
				}

				if(typeof allowMuti === "undefined"){
					allowMuti = true;
				}

				var lineArr = this.text.split("\n");
				var newText = "";
				if(!allowMuti && lineArr.length > 1){
					alert("您选中了多行文本，本操作无法完成");
				}

				var len = lineArr.length - 1;
				for(var i = 0; i <= len; i++){
					var newLine = lineArr[i];
					if(type === 1){// type为1，加前缀
						newLine = pre + newLine;
					} else if(type === 2) {// type为2，前后加标记
						newLine = pre + newLine + pre;
					} else {// type非1和2，则type作为后缀使用
						newLine = pre + newLine + type;
					}

					newText += newLine;
					if(i !== len){
						newText += "\n";
					}
				}

				$container.val($container.val().substr(0, this.start) + newText + $container.val().substr(this.end));
				container.selectionEnd = container.selectionStart = this.start;
//				container.selectionEnd = this.start + newText.length;
				$container.focus();
			}
		}
	}
})(jQuery);

