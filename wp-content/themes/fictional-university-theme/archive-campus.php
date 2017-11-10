<?php

get_header(); 
university_page_banner(array(
  'title' => 'All Campuses',
  'subtitle' => 'We have several conveniently located campuses to explore!'
));
?>

<div class="container container--narrow page-section">

  <div class="acf-map">
  <?php
    while(have_posts()) {
      the_post(); 
      $mapLocation = get_field('map_location');
      $lat = $mapLocation['lat'];
      $lng = $mapLocation['lng'] ?>

      <div class="marker" data-lat="<?php echo $lat; ?>" data-lng="<?php echo $lng; ?>">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php echo $mapLocation['address']; ?>
      </div>

    <?php } ?>
  </div>
</div>

<?php get_footer();

?>