jQuery(document).ready(function($){
  var movieSortList = $('ul.movie-sort-list');
  var loading = $('.loading');
  var saveMsg = $('.order-save-msg');
  var saveErr = $('.order-save-err');

  movieSortList.sortable({
    update:function(e,ui) {
      loading.show();

      $.ajax({
        url:movieSort.ajaxurl,
        type:'post',
        dataType:'json',
        data:{
          action:'save_order',
          order:movieSortList.sortable('toArray'),
          token:movieSort.token,
        },
        success:(res) => {
          loading.hide();
          if (res.success) {
            saveMsg.show();
            setTimeout(function(){saveMsg.hide()},3000);
          } else {
            saveErr.text(res.responseText);
            saveErr.show();
            setTimeout(function(){saveErr.hide()},3000);
          }
        },
        error:(err) => {
          saveErr.text(err.responseText);
          saveErr.show();
          setTimeout(function(){saveErr.hide()},3000);
        }
      })
    },


  })

});