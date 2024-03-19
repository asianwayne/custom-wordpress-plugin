const loadPostsByRest = document.getElementById('wp-learn-rest-api-button');
if (loadPostsByRest) {


  loadPostsByRest.addEventListener('click',function(){
    //用到wp api client 
    const allPosts = new wp.api.collections.Posts();
    allPosts.fetch().done(
      function (posts) {
        const textarea = document.getElementById("wp-learn-posts");

        //posts 从 api 获取 
        posts.forEach(function(post){

          textarea.value += post.title.rendered + '\n';



        });

      }
      )



  });
}

/**
 * Clear the textarea button 
 */
const clearPostsButton = document.getElementById('wp-learn-clear-posts'); 

if (clearPostsButton) {

  clearPostsButton.addEventListener('click',function(){
    const textarea = document.getElementById('wp-learn-posts');
    textarea.value = ''


  });
}