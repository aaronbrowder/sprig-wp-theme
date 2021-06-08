<?php 

/*
Template Name: Hide Title
*/

page_template(function() { ?>
   <div class="page">
      <?php the_content(); ?>
   </div>
<?php },
// don't show the title
false);