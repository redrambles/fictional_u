<?php
/*
Plugin Name: Red Count Programs
Description: A plugin that will create a shortcode to count posts of the program post type
*/

// Usage: [program_count]
add_shortcode('program_count', 'red_program_count_function');

function red_program_count_function(){
  $count = wp_count_posts('program');
  return $count->publish;
}
