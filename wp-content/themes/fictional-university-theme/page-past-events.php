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
      while( $pastEvents->have_posts() ) : $pastEvents->the_post(); ?>
          <div class="event-summary">
            <a class="event-summary__date passed t-center" href="#">
              <span class="event-summary__month"><?php 
              $eventDate = new DateTime( get_field('event_date') );
              echo $eventDate->format('M'); 
              ?></span>
              <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>  
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <p><?php if( has_excerpt() ) { 
                          the_excerpt(); } else { 
                            echo wp_trim_words(get_the_content(), 18);
                          } ?> 
                <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
              </p>
            </div>
        </div>
    <?php 
      endwhile;
      echo paginate_links(array(
      'total' => $pastEvents->max_num_pages
    ));
  ?>
</div>

<?php get_footer();

?>