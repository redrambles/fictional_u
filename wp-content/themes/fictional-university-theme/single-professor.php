<?php
  
  get_header();

  while(have_posts()) {
    the_post(); 
    university_page_banner();
?>

    <div class="container container--narrow page-section">
          
      <div class="generic-content">
        <div class="row group">
          <div class="one-third">
            <?php the_post_thumbnail('professorPortrait'); ?>
          </div>
          <div class="two-thirds">
            <?php the_content(); ?>
          </div>
        </div>

      </div>

      <?php
        $relatedPrograms = get_field('related_program');

        if ($relatedPrograms) { 
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
          echo '<ul class="link-list min-list">';
          foreach( $relatedPrograms as $program ){ 
            echo '<li><a href="' . get_the_permalink($program) . '">' . get_the_title($program) . '</a></li>';
          } 
            echo '</ul>';
        } ?>

    </div>
    

    
  <?php }

  get_footer();

?>