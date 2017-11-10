<?php
/*
*
* This is the template for our past events
*
*/
get_header(); 
university_page_banner(array(
  'title' => 'Past Events',
  'subtitle' => 'Check out what happened!'
));
?>

<div class="container container--narrow page-section">
<?php
    $today = date('Ymd');
    $args = array(
      'post_type' => 'event',
      'meta_key' => 'event_date',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
      'paged' => get_query_var( 'paged', 1 ),
      'meta_query' => array(
        array(
        'key' => 'event_date',
        'compare' => '<',
        'value' => $today,
        'type' => 'numeric'
        )
      ),
    );
    $pastEvents = new WP_Query($args);
      while( $pastEvents->have_posts() ) : $pastEvents->the_post(); 
      
        get_template_part('template-parts/content', 'event');

      endwhile;
      echo paginate_links(array(
      'total' => $pastEvents->max_num_pages
    ));
  ?>
</div>

<?php get_footer();

?>