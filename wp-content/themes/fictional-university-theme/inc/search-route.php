<?php
// For the Live Search feature
// Registering our own rest route so that we can return just the info we need and include all of our posts types
// Can test with Postman or browser at $site_url()/university/v1/search=?tern
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
    'post_type' => array( 'post','page', 'professor', 'program', 'campus', 'event' ),
    's' => sanitize_text_field( $data['term'] ) // 's' = 'search'
  ));

  // this is what we will be returning
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
        'type' => get_post_type(),
        'authorName' => get_author_name( )
      ));
    }
    
    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));
      $description = NULL;
      has_excerpt() ? $description = get_the_excerpt() : $description = wp_trim_words(get_the_content(), 18);
      array_push( $results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'month' => $eventDate->format('M'),
        'day' => $eventDate->format('d'),
        'description' => $description
      ));
    }

    if (get_post_type() == 'program') {
      $relatedCampuses = get_field('related_campus');

      if ( $relatedCampuses ){
        foreach( $relatedCampuses as $campus ){
          array_push($results['campuses'], array(
            'title' => get_the_title($campus),
            'permalink' => get_the_permalink($campus)
          ));
        }
      }

      array_push( $results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'id' => get_the_ID()
      ));
    }

    if (get_post_type() == 'campus') {
      array_push( $results['campuses'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }

    if (get_post_type() == 'professor') {
      array_push( $results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'image' => get_the_post_thumbnail_url( get_the_ID(), 'professorLandscape' )
      ));
    }
  
  }

  // Create second loop to look for custom fields that relate programs to professors - only if there are programs in the results

  if ( $results['programs'] ) {
    $programsMetaQuery = array('relation' => 'OR');
      foreach($results['programs'] as $item) {
        array_push($programsMetaQuery, array(
          'key' => 'related_program',
          'compare' => 'LIKE',
          'value' => '"' . $item['id'] . '"'
        ));
      }

      $programRelationshipQuery = new WP_Query( array (
        'post_type' => array('professor', 'event'),
        'meta_query' => $programsMetaQuery
      ));
    
      while ($programRelationshipQuery->have_posts()) {
        $programRelationshipQuery->the_post(); 
    
          if (get_post_type() == 'professor') {
            array_push( $results['professors'], array(
              'title' => get_the_title(),
              'permalink' => get_the_permalink(),
              'image' => get_the_post_thumbnail_url( get_the_ID(), 'professorLandscape' )
          ));
          
          if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = NULL;
            has_excerpt() ? $description = get_the_excerpt() : $description = wp_trim_words(get_the_content(), 18);
            array_push( $results['events'], array(
              'title' => get_the_title(),
              'permalink' => get_the_permalink(),
              'month' => $eventDate->format('M'),
              'day' => $eventDate->format('d'),
              'description' => $description
            ));
          }
        }
      }
    
      // Remove any duplicates if both queries return the same professor
      $results['professors'] = array_values( array_unique( $results['professors'], SORT_REGULAR ) );
      $results['events'] = array_values( array_unique( $results['events'], SORT_REGULAR ) );
      
  }

  

  return $results;
}