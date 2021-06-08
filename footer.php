      </div>
      <footer class="footer">
         <div class="container">
            <div class="footer-social">
               <?php if (!empty(get_option('facebook'))) { ?>
                  <a href="<?php echo get_option('facebook') ?>" target="_blank" class="social-link">
                     <i class="fab fa-facebook-f"></i>
                  </a>
               <?php }
               if (!empty(get_option('twitter'))) { ?>
                  <a href="<?php echo get_option('twitter') ?>" target="_blank" class="social-link">
                     <i class="fab fa-twitter"></i>
                  </a>
               <?php }
               if (!empty(get_option('instagram'))) { ?>
                  <a href="<?php echo get_option('instagram') ?>" target="_blank" class="social-link">
                     <i class="fab fa-instagram"></i>
                  </a>
               <?php }
               if (!empty(get_option('youtube'))) { ?>
                  <a href="<?php echo get_option('youtube') ?>" target="_blank" class="social-link">
                     <i class="fab fa-youtube-square"></i>
                  </a>
               <?php }
               if (!empty(get_option('linkedin'))) { ?>
                  <a href="<?php echo get_option('linkedin') ?>" target="_blank" class="social-link">
                     <i class="fab fa-linkedin"></i>
                  </a>
               <?php } ?>
            </div>
            <div class="footer-contact">
               <h4><?php echo get_bloginfo('name'); ?></h4>
               <p>
                  <?php echo get_option('email') ?>
               </p>
               <p><a href="<?php echo custom_text('contact-us-url') ?>"><?php echo custom_text('contact-us-string') ?></a></p>
            </div>
         </div>
      </footer>
      <?php wp_footer(); ?>
   </body>
</html>
