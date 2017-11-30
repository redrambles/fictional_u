<?php

//// CUSTOM POST TYPES

function university_post_types(){

  // Campus
  register_post_type( 'campus', array(
    'capability_type' => 'campus',
    'map_meta_cap' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Campuses',
      'add_new_item' => 'Add New Campus',
      'edit_item' => 'Edit Campus',
      'all_items' => 'All Campuses',
      'singular_name' => 'Campus'
    ),
    'menu_icon' => 'dashicons-location-alt',
    'has_archive' => true,
    'rewrite' => array(
      'slug' => 'campuses'
    ),
    'supports' => array('title', 'editor', 'excerpt')
  )
);

  // Event
  register_post_type( 'event', array(
      'capability_type' => 'event',
      'map_meta_cap' => true,
      'public' => true,
      'labels' => array(
        'name' => 'Events',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'all_items' => 'All Events',
        'singular_name' => 'Event'
      ),
      'menu_icon' => 'dashicons-calendar',
      'has_archive' => true,
      'rewrite' => array(
        'slug' => 'events'
      ),
      'supports' => array('title', 'editor', 'excerpt')
    )
  );

  // Program
  register_post_type( 'program', array(
    'public' => true,
    'labels' => array(
      'name' => 'Program',
      'add_new_item' => 'Add New Program',
      'edit_item' => 'Edit Program',
      'all_items' => 'All Programs',
      'singular_name' => 'Program'
    ),
    'menu_icon' => 'dashicons-awards',
    'has_archive' => true,
    'rewrite' => array(
      'slug' => 'programs'
    ),
    'supports' => array('title')
  ));

  // Professors
  register_post_type( 'professor', array(
    // building a custom REST route instead
    //'show_in_rest' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Professor',
      'add_new_item' => 'Add New Professor',
      'edit_item' => 'Edit Professor',
      'all_items' => 'All Professors',
      'singular_name' => 'Professor'
    ),
    'menu_icon' => 'dashicons-welcome-learn-more',
    'supports' => array('title', 'editor', 'thumbnail')
  ));

}

add_action('init', 'university_post_types');


////// EVENTS ADMIN SCREEN

// Add a custom column for EVENT date - header
function university_columns_head_only_event($defaults) {
    $defaults['event_date'] = 'Event Date';
    return $defaults;
}
add_filter('manage_event_posts_columns', 'university_columns_head_only_event', 10);

// Add a custom column for EVENT date - content
function university_columns_content_only_event($column_name, $post_ID) {
    if ($column_name == 'event_date') {
      $eventDate = new DateTime( get_post_meta( get_the_ID(), 'event_date', true ) );
      echo $eventDate->format('d M, Y');
    }
}
add_action('manage_event_posts_custom_column', 'university_columns_content_only_event', 10, 2);

// Make event_date column sortable (and keep the title and date sortable too)
function event_date_sortable_columns() {
  return array(
    'title' => 'title',
    'date' => 'date',
    'event_date' => 'event_date'
  );
}
add_filter( 'manage_edit-event_sortable_columns', 'event_date_sortable_columns' );

// Determine HOW we want the now sortable event_date column data to be sorted
function event_date_custom_orderby( $query ) {
  // only in admin please
  if ( ! is_admin() )
    return;

  $orderby = $query->get( 'orderby');
  
  // only for the 'event_date' custom field - when it is clicked on at the top of the column
  if ( 'event_date' == $orderby ) {
    $query->set( 'meta_key', 'event_date' );
    $query->set( 'orderby', 'meta_value_num' );
  }
}

add_action( 'pre_get_posts', 'event_date_custom_orderby' );

////// END EVENTS ADMIN SCREEN