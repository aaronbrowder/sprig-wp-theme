<h1 class="post-title">
   <?php the_title(); ?>
</h1>

<blockquote class="post-meta">
   <?php
   the_custom_author();
   the_date();
   ?>
</blockquote>

<?php the_content(); ?>

<hr/>
<br/>

<div class="blog-footer">
   
   <div class="blog-share">
      <h4>Share this</h4>
      <?php
      $url = urlencode(get_permalink());
      $title = get_the_title();
      $span_str = '<span class="entry-title-primary">';
      if (startsWith($title, $span_str)) {
         $span_length = strlen($span_str);
         $title = substr($title, $span_length);
         $end_span_pos = strpos($title, '</span>');
         $title = substr($title, 0, $end_span_pos);
      }
      $subject = urlencode($title . ' – ' . get_bloginfo('name'));
      $email_body = urlencode("Hi,\n\nI thought you'd like this:\n" . get_permalink());
      ?>
      <a class="blog-share-facebook" 
         href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>"
         target="_blank">
        <i class="fab fa-facebook-square"></i>
      </a>
      <a class="blog-share-twitter" 
         href="https://twitter.com/share"
         target="_blank">
        <i class="fab fa-twitter"></i>
      </a>
      <a class="blog-share-email"
         href="mailto:?subject=<?php echo $subject; ?>&body=<?php echo $email_body; ?>"
         target="_blank">
         <i class="fas fa-envelope"></i>
      </a>
   </div>
   
</div>

<nav class="blog-paginator">
	<ul>
	   <?php
	   $next_post_link = get_next_post_link('%link', '&laquo;&nbsp; %title');
	   if (!empty($next_post_link)) { ?>
	      <li><?php echo $next_post_link ?></li>
	   <?php } ?>
		<li><?php previous_post_link('%link', '%title &nbsp;&raquo;'); ?></li>
	</ul>
</nav>