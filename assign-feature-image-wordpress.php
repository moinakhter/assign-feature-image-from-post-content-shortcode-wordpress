function assign_feature_image(){

    global $wpdb;
// get all the post
    $args = array(
        'post_type'=> 'post',
        'orderby'    => 'ID',
        'post_status' => 'publish',
        'order'    => 'DESC',
        'posts_per_page' => -1 // this will retrive all the post that is published
    );
    $result = new WP_Query( $args );
    if ( $result-> have_posts() ) :
      while ( $result->have_posts() ) : $result->the_post();
          //getting content of the post 
          $content = get_the_content(get_the_ID());
          //fetching image url
          preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $content, $urls);
          $urls = $urls[1];
          if(empty($urls[0]))
              continue;
          // we get images as array, if we have more than one image then we can use loop.                            
          $get_attachment_id = $urls[0];
          //get image url attachment id                            
          $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $get_attachment_id ));
          $get_attachment_id = $attachment[0];
            // assigning image to the post.
          add_post_meta(get_the_ID(), '_thumbnail_id', $get_attachment_id, true);
      endwhile;
    endif; wp_reset_postdata();
}

add_shortcode("assign_feature_image","assign_feature_image");
