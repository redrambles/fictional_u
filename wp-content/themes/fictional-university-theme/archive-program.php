<?php

get_header(); 
university_page_banner(array(
  'title' => 'All Programs',
  'subtitle' => 'There\'s something for everyone! Find your program!'
));
?>

<div class="container container--narrow page-section">
<?php
  while(have_posts()) {
    the_post(); ?>
      <ul class="link-list min-list">
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      </ul>
  <?php }
  echo paginate_links();
?>

</div>

<?php get_footer();

?>