<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hastart
 */
tie_setPostViews();
get_header();

?>
<div class="container">
  <section class="index_cont list_content">
    <div class="main_cont clear">
      <div class="list-cont fl">
        <nav class="breadcrumb"> 
          <?php echo tie_breadcrumbs(); ?> </nav>

         
        <div class="recommend_list mod_list">
          <div class="detail_main">
            <h3 class="movie-title"><?php the_title(); ?></h3>
            <p class="movie-info">Release Date:<?php echo get_post_meta( get_the_ID(), 'release_date', true ); ?></p>
            

      
    <!-- end of above single banner -->
            <div class="detail_article movie-container">
              <div class="img-con">
                <img class="movie-thumb" src="<?php the_post_thumbnail_url() ?>">
              </div>
              
              <div class="info-con">
                <h2>Details / 描述</h2>
                <p class="movie-info"><?php echo get_post_meta(get_the_ID(),'details',true) ?></p>
                <hr>
                <h2 style="margin-bottom: 1rem;">Movie Info</h2>
                <ul>
                  <li>Mpaa Rating:<?php echo get_post_meta( get_the_ID(), 'mpaa_rating', true ); ?></li>
                  <li>Director:<?php echo get_post_meta( get_the_ID(), 'director', true ); ?></li>
                  <li>Stars:<?php echo get_post_meta( get_the_ID(), 'stars', true ); ?></li>
                  <li>Runtime:<?php echo get_post_meta( get_the_ID(), 'runtime', true ); ?></li>
                  <li>Trailor:<?php echo get_post_meta( get_the_ID(), 'trailor', true ); ?></li>
                </ul>
              </div>
              

            </div>


     

            <div class="article_footer clear">
              <div class="fr tag"><?php the_tags() ?></div>
              <div class="bdsharebuttonbox fl share"> 
                <!-- 这里放分享小工具 -->
                <!-- <a>分享：</a> <a href="#" class="bds_weixin fa fa-weixin" data-cmd="weixin" title="分享到微信"></a> <a href="#" class="bds_sqq fa fa-qq" data-cmd="sqq" title="分享到QQ好友"></a> <a href="#" class="bds_tsina fa fa-weibo" data-cmd="tsina" title="分享到新浪微博"></a>  -->
              </div>
              
            </div>
            <!-- 广告位ad4  -->

            <div class="post-navigation clear">
              <div class="post-previous fl"><?php echo get_previous_post_link() ?></div>
              <div class="post-next fr"><?php echo get_next_post_link() ?></div>
            </div>
          </div>
          
        </div>
      </div>

      <?php get_sidebar()?>
    </div>
  </section>
</div>
  

<?php
//get_sidebar();
get_footer();
