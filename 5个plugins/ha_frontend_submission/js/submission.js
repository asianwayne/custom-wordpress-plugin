jQuery(document).ready( function($) {

        // const username = '<?php echo get_option('application_username'); ?>';
        // const password = '<?php echo get_option('application_password'); ?>';
        // do something with the data
        // 或者直接从后台获取username和password的值粘贴到上面这样就不用发送wp ajax 了
        
  // const credentials = `${username}:${password}`;
  // const encodedCredentials = btoa(credentials);

  $("#submission_form").on('submit',function(e) {
  e.preventDefault();

  // if (username.length === 0 || password.length === 0) {
  //   console.log('没有授权用户信息,请去管理后台添加授权用户');
  //   return;
  // }

  
  //form validation
  if ($(this)[0].checkValidity() && $("#verify").val() == 6547) {
    
    $.ajax({
    url:ha_submission.root + 'ha_submission/v1/submission/',

   beforeSend: function(xhr) {
    xhr.setRequestHeader("X-WP-Nonce",ha_submission.nonce );
  },
  method:'POST',

  data:$(this).serialize(),

    success: res => {
      
      console.log(res);

      $(this)[0].reset();

      $("#formAlert").html("<h2 style='color:green'>成功提交信息，页面即将跳转</h2>");

      setTimeout(function (argument) {
        // body... 
         $("#formAlert").html('');
        //window.location = ha_submission.site_url;
      },1500);
      
      console.log('成功提交数据，页面即将跳转');
    },
    
    error: err => {
      $("#formAlert").html("<h2 style='color:red'>请求内部信息错误请重新检查请求</h2>")
      console.log(err.responseText); 
      console.log('Failed to complete the request');
    }
  })

  } else {
    $("#verifyAlert").html("<p style='color:red'>答案不正确请重新输入</p>");
    setTimeout(function(){$("#verifyAlert").html('')},1500);
    console.log('验证数据不正确请重新输入!');
  }

}); 

  });