$(function(){
	document.getElementById("upimg").onchange = function(){
		var files = this.files;

		html5up.getImg(files);
	}

	var body = $("body").get(0);
	body.ondragover = function (ev) {
		$(this).css("opacity", "0.4");
		return false;
	}

	body.ondragleave = function (){
		$(this).css("opacity", "1");
	}

	body.ondrop = function(ev) {
		html5up.getImg(ev.dataTransfer.files);
		$(this).css("opacity", "1");
		return false;
	}

	$("#uploadbutton").click(function(){
		var idsArr = $("input[name=upid]").toArray();
		$(".alertBg").show();

		html5up.uploadImg(idsArr);

		return false;
	});
});

var html5up = {
	fileList : [],
	up : 0,
	upcount : 0,
	upcureentr : 0,
	getImg : function(files){
		for(var i = 0; i < files.length; i++){
			var filetype = files[i].type;
			var filesize = files[i].size;
			if(filetype != 'image/jpeg' && filetype != 'image/png' && filetype != 'image/gif'){
				continue;
			}

			//文件大小限定2M以下
			if(filesize > 2097152){
				continue;
			}

			this.fileList.push(files[i]);
			this.viewImg(files[i]);
			this.up++;
		}
	},
	viewImg : function(file){
		var reader = new FileReader(), htmlImage;
		var i = this.up;
		reader.onload = function(e) {
			var liDom = $($("#upview").html());
			liDom.find("img").attr("src", e.target.result);
			liDom.find("[_type=del]").click(function(){
				$(this).parent().remove();
			});
			liDom.find("[name=title]").attr("id", "title" + i);
			liDom.find("[name=title]").val(file.name);
			liDom.find("[name=summary]").attr("id", "summary" + i);
			liDom.find("[name=upid]").val(i);

			$("#uploadview").append(liDom);
		}

		reader.readAsDataURL(file);
	},
	uploadImg : function (inputArr){
		var uptype = $("#uptype").val();

		for(var i = 0; i < inputArr.length; i++){
			var id = inputArr[i].value;
			if(!id){
				continue;
			}
			this.upcount++;
			var fd = new FormData();

			fd.append('source', uptype);
			if(uptype != 0){
				fd.append('key', $("#key").val());
			} else {
				fd.append('aid', $("[name=aid]").val());
				fd.append('title', $("#title" + id).val());
				fd.append('summary', $("#summary" + id).val());
			}

			fd.append('upimg', this.fileList[id]);
			this.uploadAction(fd);
		}
	},
	uploadAction : function (fd){
		var xhr = new XMLHttpRequest();
		var url = '/admin/photo/action';

		xhr.open('POST', url, true);

		xhr.upload.onprogress = function (ev) {
			if(ev.lengthComputable) {
				if(ev.loaded / ev.total != 1){
					var a = $(".meter-progress").html() + "<br />" + (ev.loaded +" "+ ev.total);
					$(".meter-progress").html(a);
				}
			}
		}

		xhr.addEventListener("load", function(e){
			html5up.upcureentr++;
			if(html5up.upcount <= html5up.upcureentr){
				alert("上传成功！");
				$(".alertBg").hide();
				try{
					uploadCallback();
				} catch(e) { }
			}
		}, false);

		xhr.send(fd);
	}
};