<?php
  
  get_header();

  while(have_posts()) :
    the_post(); 
    university_page_banner();
  ?>



  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

    <?php //  var_dump($GLOBALS['wpdb']->tables); ?>

    <div class="generic-content"><?php the_content(); ?></div>
      
      <div class="acf-map">
      
        <?php
        $mapLocation = get_field('map_location');
        $lat = $mapLocation['lat'];
        $lng = $mapLocation['lng'] ?>

        <div class="marker" data-lat="<?php echo $lat; ?>" data-lng="<?php echo $lng; ?>">
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <?php echo $mapLocation['address']; ?>
        </div>
      </div>

      <?php
        // Related Programs
        $args = array(
          'posts_per_page' => -1,
          'post_type' => 'program',
          'orderby' => 'title',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'related_campus',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"' // to handle the serialization of the data
            )
          )
        );
        $relatedPrograms = new WP_Query($args);

        if ( $relatedPrograms->have_posts() ) :
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium"> Programs Available at this Campus</h2>';
          echo '<ul class="min-list link-list">';
          while($relatedPrograms->have_posts() ) : $relatedPrograms->the_post(); ?>
            <li>
              <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </li>
          <?php endwhile;
            echo '</ul>';
            endif;
          wp_reset_postdata();  
          ?>

        
    </div><!-- .container.... -->
    
  <?php endwhile;

  get_footer();

?>