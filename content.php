<div class="blog-excerpt-container">
      
   <h2>
      <a href="<?php the_permalink(); ?>">
         <?php the_title(); ?>
      </a>
   </h2>
   
   <blockquote>
      <?php
      the_custom_author();
      the_date();
      ?>
   </blockquote>
   
   <?php the_excerpt(); ?>

</div>