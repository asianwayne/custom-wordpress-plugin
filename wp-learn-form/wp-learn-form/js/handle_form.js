jQuery(document).ready(($) => {

	console.log('plugin loaded form'); 

	console.log($("#wlf_form").length);

	

	if ($("#wlf_form").length > 0) {

		$("#wlf_form").on('submit',function(e){
			e.preventDefault();

			if (this.checkValidity()) {
				console.log('form checked');

				var formData = new FormData(this);
				

				formData.append('action','handle_wlf_form_ajax');
				formData.append('param','submit_wlf_form');

				//首先执行不上传文件版本 
				$.ajax({
					url:wlf_handle.ajax_url,
					type:'POST',
					data:formData,
					contentType: false,
					processData: false,
					success:function(res) {
						console.log(res);
						try {
							// statements
							res = JSON.parse(res);
						
						if (res.status == 1) {
							
							$("#wlf_form")[0].reset();

							showInfo(res.message,'green');
						} else {
							showInfo(res.message,'red');
						}

						} catch(e) {
							// statements  如果wp_mail 失败，单独返回wp_error
							console.log('数据返回错误或者返回的不是json' + e);
							
							showInfo(res,'red');
						}
						

					},
					error:function(err){
						console.log(err);
						showInfo(err,'red');
						
					}

				});

			} else {
				console.log('form not valid');
				showInfo('form not valid','red');
			}
		});

	}

});


function showInfo(data,color) {

	jQuery(".warning-info").html(data);
	jQuery(".warning-info").css('color',color);
	jQuery(".warning-info").show();


	setTimeout(function(){
		jQuery(".warning-info").fadeOut();
	},2000);
}