<?php get_header(); ?>

	<div class="container">
	   <div class="page">
		      
   		<?php if (have_posts()) { ?>
   		   
   		   <h1>
   		      Blog
   		      <span class="text-light">
   		         &middot; Sprig Institute
		         </span>
   		      <?php
   		      //page_number();
   		      ?>
		      </h1>
		      
   			<?php while (have_posts()) { 
   			   the_post();
   				get_template_part('content', get_post_format());
   			} ?>
   			
            <nav class="blog-paginator">
            	<ul>
            		<li><?php next_posts_link('&#9666;&nbsp; Earlier Posts'); ?></li>
            		<li><?php previous_posts_link('Later Posts &nbsp;&#9658;'); ?></li>
            	</ul>
            </nav>
   
   		<?php } ?>
   		
		</div>
	</div>

<?php get_footer(); ?>
