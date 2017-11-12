<?php
// registering our own rest route so that we can return just the info we need and include all of our posts types
function universityRegisterSearch() {
  register_rest_route( 'university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE, //'GET'
    'callback' => 'universitySearchResults'
  ));
}
add_action('rest_api_init', 'universityRegisterSearch');

// WP will automatically convert PHP to JSON for us - so yay!
function universitySearchResults($data) {
  $searchQuery = new WP_Query(array(
    'post_type' => array('post','page', 'professor', 'program', 'campus', 'event'),
    's' => sanitize_text_field($data['term']) // 's' = 'search'
  ));

  // this cute little multi-dimensional array will hold our return results in an organized way
  $results = array(
    'generalInfo' => array(

    ),
    'professors' => array(

    ),
    'programs' => array(

    ),
    'events' => array(

    ),
    'campuses' => array(

    )
  );

  while($searchQuery->have_posts()) {
    $searchQuery->the_post();

    if (get_post_type() == 'post' || get_post_type() == 'page') {
      array_push( $results['generalInfo'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
    if (get_post_type() == 'event') {
      array_push( $results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
    if (get_post_type() == 'program') {
      array_push( $results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
    if (get_post_type() == 'campus') {
      array_push( $results['campuses'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
    if (get_post_type() == 'professor') {
      array_push( $results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
  
  }

  return $results;
}