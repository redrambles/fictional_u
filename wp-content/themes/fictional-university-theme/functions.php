<?php

require get_theme_file_path('/inc/search-route.php');

function university_custom_rest(){
  register_rest_field('post', 'authorName', array(
    'get_callback' => function(){
      return get_the_author();
    }
  ));
  // you can add more here if you like - for a custom field, for example:
  // register_rest_field('professor', 'subject', array ( 'get_callback => function(){ return some crap }'))
}
add_action('rest_api_init', 'university_custom_rest');
// Arguments are made optional so that defaults can be used by simply calling the function without passing any args
function university_page_banner( $args = NULL ){
  $default_title = get_the_title();
  $default_subtitle = get_field( 'page_banner_subtitle' );
  $uploaded_photo = get_field( 'page_banner_background_image' );
  $fallback_photo = get_theme_file_uri( '/images/ocean.jpg' );
 
  // if desired, the function can be called with $args as an associated array to set custom instances of any of the possible arguments
  if ( !$args['title'] ) $args['title'] = $default_title;
  if ( !$args['subtitle'] ) $args['subtitle'] = $default_subtitle; 
  if ( !$args['photo'] ) {
    if ( $uploaded_photo ) {
      $args['photo'] = $uploaded_photo['sizes']['pageBanner'];
    } else {
      $args['photo'] = $fallback_photo;
    }  
  } 
  echo '<div class="page-banner">';
  echo    '<div class="page-banner__bg-image" style="background-image: url(\''. $args['photo'] .'\'">"></div>';
  echo    '<div class="page-banner__content container container--narrow">';
  echo     '<h1 class="page-banner__title">' . $args['title'] . '</h1>';
  echo      '<div class="page-banner__intro">';
  echo       '<p>' . $args['subtitle'] . '</p>';
  echo      '</div>';
  echo    '</div>'; 
  echo '</div>';
} 

function university_files() {
  wp_enqueue_script('google-map', '//maps.googleapis.com/maps/api/js?key=AIzaSyDOjhc9MTw58mOKRgHAATgDrlFtsESahTA', NULL, '1.0', true);
  wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());
  // handle has to match the handle of the script you want to make flexible
  // Will create a 'universityData' JS object which you can view in the source and can access in Search.js, for example
  wp_localize_script( 'main-university-js', 'universityData', array(
    'root_url' => get_site_url()
  ));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size( 'professorLandscape', 400, 260, true );
  add_image_size( 'professorPortrait', 480, 650, true );
  add_image_size( 'pageBanner', 1500, 350, true );
  
}

add_action('after_setup_theme', 'university_features');

// Archive: Only show events that haven't passed and order by event date from soonest to farthest away
function university_adjust_archives($query) {

  if( !is_admin() && is_post_type_archive('campus') && is_main_query() ) {
    $query->set('posts_per_page', 50 );
  }

  if( !is_admin() && is_post_type_archive('program') && is_main_query() ) {
    $query->set('posts_per_page', 50 );
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
  }

  if( !is_admin() && is_post_type_archive('event') && is_main_query() ) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      )
    ));
  }
}
add_action('pre_get_posts', 'university_adjust_archives');

function university_map_key($api){
   $api['key'] = 'AIzaSyDOjhc9MTw58mOKRgHAATgDrlFtsESahTA';
   return $api;
}

add_filter('acf/fields/google_map/api', 'university_map_key');