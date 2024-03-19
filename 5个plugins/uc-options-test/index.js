jQuery(document).ready(($) => {

  $("#og-img-btn").on('click',function(e){

    const frame = wp.media({
      title:'SELECT',
      button:{
        text:'SELECT'
      },
      multiple:false
    }); 
    frame.on('select',function() {
      const attachment = frame.state().get('selection').first().toJSON(); 
      //如果要获取不同尺寸的url  就是 attachment.sizes.opengraph.url 
      $("#up_og_image").val(attachment.url); 
      $("#og-img-preview").attr('src',attachment.url);

    });


    frame.open();


  });

});