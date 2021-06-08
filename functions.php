<?php

//////////////////////////////////////////////////////////////////////
// Add scripts and stylesheets
add_action('wp_enqueue_scripts', 'mytheme_scripts');
function mytheme_scripts() {
  wp_enqueue_style('site', get_template_directory_uri() . '/css/site.css');
	wp_enqueue_script('front-end', get_template_directory_uri() . '/js/front-end.js');
	wp_enqueue_script('font_awesome', 'https://kit.fontawesome.com/93e7c8feba.js');
}

add_action('admin_enqueue_scripts', 'admin_scripts');
function admin_scripts() {
  wp_enqueue_media();
	wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery-3.1.1.min.js');
	wp_enqueue_script('media_selector', get_template_directory_uri() . '/js/media-selector.js', array('jquery'));
	
	$data = array('my_saved_attachment_post_id' => get_option('media_selector_attachment_id', 0));
    wp_localize_script( 'media_selector', 'php_vars', $data );
}

//////////////////////////////////////////////////////////////////////
// Add Google Fonts
add_action('wp_print_styles', 'mytheme_google_fonts');
function mytheme_google_fonts() {
	wp_register_style('OpenSans', 'https://fonts.googleapis.com/css?family=Open+Sans');
	wp_register_style('OpenSansCondensedBold', 'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700');
   wp_register_style('Vollkorn', 'https://fonts.googleapis.com/css?family=Vollkorn');
	wp_enqueue_style('OpenSans');
	wp_enqueue_style('OpenSansCondensedBold');
   wp_enqueue_style('Vollkorn');
}

//////////////////////////////////////////////////////////////////////
// Add custom query variables
function mytheme_query_vars($qvars) {
    $qvars[] = 'submitted';
    return $qvars;
}
add_filter('query_vars', 'mytheme_query_vars');

//////////////////////////////////////////////////////////////////////
// Set up admin page to allow editing theme parameters

add_action('admin_menu', 'theme_params_add_menu');
function theme_params_add_menu() {
   add_menu_page('Theme_Params', 'Theme Params', 'manage_options', 'theme_params', 'theme_params_page', null, 4);
}

function theme_params_page() { ?>
   <div class="wrap">
      <h1>Theme Parameters</h1>
      <form method="post" action="options.php">
         <?php
            settings_fields('theme-params');
            do_settings_sections('theme-params');
            submit_button();
         ?>
      </form>
   </div>
<?php }

function email_callback() { ?>
   <input type="text" name="email" size="50" value="<?php echo get_option('email'); ?>" />
<?php }

function facebook_callback() { ?>
   <input type="text" name="facebook" size="70" value="<?php echo get_option('facebook'); ?>" />
<?php }
 
function twitter_callback() { ?>
   <input type="text" name="twitter" size="70" value="<?php echo get_option('twitter'); ?>" />
<?php }
 
function pinterest_callback() { ?>
   <input type="text" name="pinterest" size="70" value="<?php echo get_option('pinterest'); ?>" />
<?php }
 
function instagram_callback() { ?>
    <input type="text" name="instagram" size="70" value="<?php echo get_option('instagram') ?>"/>
<?php }
 
function youtube_callback() { ?>
   <input type="text" name="youtube" size="70" value="<?php echo get_option('youtube'); ?>" />
<?php }

function linkedin_callback() { ?>
   <input type="text" name="linkedin" size="70" value="<?php echo get_option('linkedin'); ?>" />
<?php }

function header_logo_image_attachment_callback() { 
   $option_id = 'header-logo-image-attachment-id';
   $image_attachment_id = get_option($option_id); ?>
   <div class="image-preview-wrapper">
		<img class="image-preview" src="<?php echo wp_get_attachment_url($image_attachment_id); ?>" height="100"/>
	</div>
	<input class="upload-image-button button" type="button" value="<?php _e('Select Image'); ?>" />
	<input type="hidden" name="<?php echo $option_id; ?>" class="image-attachment-id" value="<?php echo $image_attachment_id; ?>">
<?php }

add_action('admin_init', 'theme_params_page_setup');
function theme_params_page_setup() {
   
   add_settings_section('content', 'Content', null, 'theme-params');
   add_settings_field('header-logo-image-attachment-id', 'Header Logo Image', 'header_logo_image_attachment_callback', 'theme-params', 'content');
   add_settings_field('email', 'Email', 'email_callback', 'theme-params', 'content');
   add_settings_field('facebook', 'Facebook URL', 'facebook_callback', 'theme-params', 'content');
   add_settings_field('twitter', 'Twitter URL', 'twitter_callback', 'theme-params', 'content');
   add_settings_field('pinterest', 'Pinterest URL', 'pinterest_callback', 'theme-params', 'content');
   add_settings_field('instagram', 'Instagram URL', 'instagram_callback', 'theme-params', 'content');
   add_settings_field('youtube', 'YouTube URL', 'youtube_callback', 'theme-params', 'content');
   add_settings_field('linkedin', 'LinkedIn URL', 'linkedin_callback', 'theme-params', 'content');
   
   register_setting('theme-params', 'header-logo-image-attachment-id');
   register_setting('theme-params', 'email');
   register_setting('theme-params', 'facebook');
   register_setting('theme-params', 'twitter');
   register_setting('theme-params', 'pinterest');
   register_setting('theme-params', 'instagram');
   register_setting('theme-params', 'youtube');
   register_setting('theme-params', 'linkedin');
}

//////////////////////////////////////////////////////////////////////
// Wordpress configuration
add_action( 'after_setup_theme', 'register_main_menu' );
function register_main_menu() {
  register_nav_menu('main-menu', __('Main Menu', 'theme-slug'));
}

//////////////////////////////////////////////////////////////////////
// Shortcodes

// [contact-form]
add_shortcode('contact-form', 'contact_form_shortcode_callback');
function contact_form_shortcode_callback($atts = [], $content = null) {
   $atts = shortcode_atts(array(
     'button-text' => 'Send', 
     'show-phone' => 'true',
     'show-address' => 'false',
     'show-message' => 'true',
     'show-preference' => 'false',
     'show-recipient' => 'false',
     'recipient-label' => 'Send to',
     'recipients' => '1,2,3,4,5'
     ),
     $atts);
	return render_php('contact-form.php', $atts, $content);
}


//////////////////////////////////////////////////////////////////////
// Helpers
function get_menu_items() {
   $locations = get_nav_menu_locations();
   $menu = wp_get_nav_menu_object($locations['main-menu']);
   return wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
}

function get_page_id($menu_item) {
   return get_post_meta($menu_item->ID, '_menu_item_object_id', true);
}

function get_menu_item_id($menu_item) {
   return $menu_item->ID;
}

function page_number() {
   global $paged;
   if ($paged > 1) { ?>
      <span class="text-light">
         &middot; Page <?php echo $paged; ?>
      </span>
   <?php }
}

function see_comments() {
   $comments_number = get_comments_number();
   if ($comments_number > 0) { ?>
      &middot;
      <a href="<?php comments_link(); ?>">
      	<?php	printf(_nx('One Comment', '%1$s Comments', $comments_number, 'comments title', 'textdomain'), number_format_i18n($comments_number)); ?>
      </a>
   <?php }
}

function the_custom_author() {
   $author = get_post_meta(get_the_ID(), 'Custom Author', true);
   if (!empty($author)) { ?>
      by <?php echo $author; ?><br/>
   <?php }
}

function the_custom_description() {
   $description = get_post_meta(get_the_ID(), 'Custom Description', true);
   if (!empty($description)) {
       echo $description;
   }
   else {
      the_excerpt();
   }
}

function get_page_title_prefix() {
   global $wp_query;
   $queried_object_id = $wp_query->queried_object_id;
   
   $title_prefix = '';
   $menu_items = get_menu_items();
   
   $current_sub_menu_items = array_filter($menu_items, function($o) use ($queried_object_id) { 
      return $o->menu_item_parent && get_page_id($o) == $queried_object_id;
   });
   
   if (!empty($current_sub_menu_items)) {
      $current_sub_menu_item = current($current_sub_menu_items);
      $parent_id = $current_sub_menu_item->menu_item_parent;
      $parent = current(array_filter($menu_items, function($o) use ($parent_id) { return $o->ID == $parent_id; }));
      if ($parent->title != get_the_title()) {
         $title_prefix = $parent->title . ' &middot; ';  
      }
   }
   
   return $title_prefix;
}

function contact_form_generate_response($type, $message){
   if ($type == 'success') return "<div class='contact-us-success'>{$message}</div>";
   else return "<div class='contact-us-error'>{$message}</div>";
}

function custom_text($id) {
   if (get_locale() == 'es_MX') {
      $id .= '-es';
   }
   return get_option($id);
}

function page_template($content_function, $show_title = true) {
   get_header(); ?>
   <div class="container">
      <?php if (have_posts()) {
         while (have_posts()) {
            the_post();
            if ($show_title) { ?>
              <h1 class="page-title">
                 <span class="text-light-green"><?php the_title(); ?></span>
              </h1>
            <?php }
            $content_function();
         }
      } ?>
   </div>
   <?php get_footer();
}

function render_php($path, $atts, $content)
{
   $GLOBALS['atts'] = $atts;
   $GLOBALS['content'] = $content;
   ob_start();
   include($path);
   $var = ob_get_contents(); 
   ob_end_clean();
   return $var;
}

function embed_video($video_url) {
   $supported = wp_get_video_extensions();
   $video_pathinfo = pathinfo($video_url);
   $video_ext = $video_pathinfo['extension'];
   if (in_array($video_ext, $supported)) {
      wp_video_shortcode(array('src'=>$video_url));
   } else {
      $video = wp_oembed_get($video_url);
      echo $video;
   }
}

function startsWith($haystack, $needle) {
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle) {
   $length = strlen($needle);
   if ($length == 0) {
      return true;
   }
   return (substr($haystack, -$length) === $needle);
}

add_theme_support('title-tag');
add_theme_support('post-thumbnails'); 

add_filter('show_admin_bar', '__return_false');