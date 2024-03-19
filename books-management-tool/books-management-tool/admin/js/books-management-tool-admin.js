(function( $ ) {
	'use strict';

	/////////////
	//use oop  //
	/////////////
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	const bookForm = $("#create-book-form");
	const createBookBtn = $("#create-book-form-btn");


	if ($("#tbl-book-list").length > 0) {
		new DataTable('#tbl-book-list',{
			order:[[8,'DESC']]
		});
	}

	if ($("#tbl-book-shelf-list").length > 0) {
		new DataTable('#tbl-book-shelf-list',{
			order:[[0,'ASC']]
		});
	}
	 

	 // if ($("#book-shelf-btn").length > 0) {
	 // 	$("#book-shelf-btn").on('click',(e) => {
	 // 		e.preventDefault();
	 // 		console.log(book_rest);

	 // 		var formData = $("#form-add-book-shelf").serialize();


	 		// $.ajax({
	 		// 	type:'POST', 
	 		// 	url:book_rest.rest_url + 'bookmanager/v1/formsubmissions',
	 		// 	data:formData,
	 		// 	success:(res) => {
	 		// 		console.log(res);
	 		// 	},
	 		// 	error:(err) => {
	 		// 		console.log(err);
	 		// 	}


	 		// });

	 // 	});


	 // }
	 //这里演示两种ajax 方法， 一种是wp ajax， 一种是rest api 
	 ///////////////////
	 //add book shelf //
	 ///////////////////

	 if ($("#book-shelf-btn").length > 0) {

	 	$("#book-shelf-btn").on('click',(e) => {
	 		e.preventDefault();

	 		var form = $("#form-add-book-shelf"); 

	 		if (form[0].checkValidity()) {

	 			var formData = form.serialize()+"&action=handle_book_ajax&param=create_book_shelf";

	 		$.post(book_rest.ajax_url,formData)
	 		.done((res) => {
	 			//也可以根据返回的status来决定是否要 location.reload()
	 			res = JSON.parse(res);
	 			form[0].reset();
	 			swal({
				  title: "Good job!",
				  text: res.message,
				  icon: "success",
				});

	 		})
	 		.fail((err) => {console.log(err)});


	 		} else {
	 			console.log('Form is not valid check the form inputs');
	 		}
	 		

	 	});


	 }

	 //////////////////////
	 //delete book shelf //
	 //////////////////////

	 $(document).on('click','.btn-delete-book-shelf',(e) => {

	 console.log('You click he right btn of delete');

	//这里可以添加一个sweetalert 来提示确认删除
	
	swal("Are you sure?", {
  dangerMode: true,
  buttons: true,
	}).then(function(data){
		if (data) {
			let shelf_id = $(e.target).attr("data-id");

	 console.log(shelf_id);

	 let postData = "action=handle_book_ajax&param=delete_book_shelf&shelf_id="+shelf_id;

	 $.post(book_rest.ajax_url,postData)
	 .done((res) => {
	 	console.log(res);
	 	swal({
	 		title: "Good job!",
				  text: res.message,
				  icon: "success",

	 	}).then(() => {
	 		location.reload();
	 	})
	 	
	 })
	 .fail((err) => {console.log(err)});


		}

	});
	 });

	 /////////////
	 //ADD BOOK //
	 /////////////
	 /// make the image review of the file input 
	$("#txt_image").on('change',function(){
	 			console.log('on change');

	 			if (this.files && this.files[0] ) {
	 				const reader = new FileReader();

	 				reader.onload = function(e){
	 					$("#book-image-preview").attr('src',e.target.result);

	 				}

	 				reader.readAsDataURL(this.files[0]);

	 			}
	 			
	 		});


	/////////////////
	//add the book //
	/////////////////
	 $("#create-book-form").on('submit',function (e) {
	 	e.preventDefault();
	 	//用this的时候要用默认的function() 而不是箭头函数
	 	if (this.checkValidity()) {

	 		console.log('You submit the form');
	 		const formData = new FormData(this);

	 		//这阶段没有formdata 因为之前preventdefault了 
	 		formData.append('action','handle_book_ajax');
	 		formData.append('param','create-book');

	 		//先执行file字段change的时候设置预览的图片


	 		//下面不能直接用$.post因为要配置processData and contentType options as false and also set the enctype attribute of the form to "multipart/form-data" to ensure that the form data is processed correctly， 所以用$.ajax 
	 		
	 		$.ajax({
	 			url:book_rest.ajax_url,
	 			type:'POST',
	 			data:formData,
	 			contentType:false,
	 			processData:false,
	 			success:(res) => {
	 				console.log(res);
	 				try {
	 					// statements
	 					const data = JSON.parse(res);

	 					console.log("+success");
	 					

	 				if (data.status) {
	 					swal({
	 						title:"Good Job",
	 						text:data.message,
	 						icon:"success"
	 					}).then(() => {
	 						bookForm[0].reset();
	 					});

	 				} else {
	 					swal({
	 						title:"Failed",
	 						text:data.message,
	 						icon:"error"

	 					});
	 				}

	 				} catch(e) {
	 					// statements
	 					
	 					console.log(e);
	 					swal({
	 						title:"error",
	 						text:'数据查询格式错误',
	 						icon:'error'
	 					});

	 				}
	 			},
	 			error:(err) => {
	 				console.log(err);
	 			}
	 		})

	 	} else {
	 		console.log('You didnt submit the form');
	 	}

	 });

	$(document).on('click','.btn-delete-book',function(e){

		swal({
			title:'are you sure you want to delete the book',
			dangerMode:true,
			buttons:true
		}).then(data => {
			if (data) {
				const book_id = $(this).data("id");

		$.ajax({
			url:book_rest.ajax_url,
			type:'POST',
			data:{
				action:'handle_book_ajax',
				param:'delete-book',
				book_id:book_id
			},
			success:res => {
				console.log(res);
				res = JSON.parse(res);


				if (res.status) {
					swal({
						title:"Good Jon",
						text:res.message,
						icon:'success'

					}).then(() => {
						location.reload();
					});


				} else {
					swal({
						title:'Error',
						text:res.message,
						icon:'error'
					});
				}
			},
			error: err => {
				console.log(err);
			}


		});

			} else {
				console.log('you didnt confirm');
			}
		}); 

	});



	

})( jQuery );
