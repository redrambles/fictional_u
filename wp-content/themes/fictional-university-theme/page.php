<?php

  get_header();

  while(have_posts()) {
    the_post(); 
    university_page_banner();
    ?>

    <div class="container container--narrow page-section">
    
    <?php
      // Grab the Parent Page ID, if it exists (so if this is a child page)
      $theParent = wp_get_post_parent_id(get_the_ID());
      if ($theParent) { ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
          <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>
      <?php }

    // is this a parent page? If so - grab ids of child pages
    $testArray = get_pages(array(
      'child_of' => get_the_ID()
    ));

    // If this is a child page or if this is a parent page
    if ($theParent or $testArray) { 
      
    // if either of these conditions are true, there is at the very least a parent page, so let's output that ?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
      <ul class="min-list">
        <?php
          // Does this page have a parent page? If so, set the $findChildrenOf variable to the parent page id
          if ($theParent) {
            $findChildrenOf = $theParent;
          } else { // otherwise, set the $findChildrenOf variable to this current page, because it is a parent page.
            $findChildrenOf = get_the_ID();
          }
          // whether this is a parent or a child page, the related child pages will show (either as children or siblings)
          wp_list_pages(array(
            'title_li' => NULL,
            'child_of' => $findChildrenOf,
            'sort_column' => 'menu_order'
          ));
        ?>
      </ul>
    </div>
    <?php } ?>
    

    <div class="generic-content">
      <?php the_content(); ?>
    </div>

  </div>
    
  <?php }

  get_footer();

?>