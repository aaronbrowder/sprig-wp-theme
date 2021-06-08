<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="Cache-Control" content="no-store" />
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

      <!-- Global site tag (gtag.js) - Google AdWords: 846232865 -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=AW-846232865"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'AW-846232865');
      </script>
    
      <?php wp_head(); ?>
   </head>

   <body>
      
      <?php
      global $wp_query;
      $queried_object = $wp_query->get_queried_object();
   	$queried_object_id = (int) $wp_query->queried_object_id;
   	
      $menu_items = get_menu_items();
      
      $menu_dictionary = array();
      $top_menu_items = array();
      $sub1_menu_items = array();
      $sub2_menu_items = array();
      $current_sub1_menu_item_id;
      $current_sub1_menu_items;
      $current_sub2_menu_items;
      
      foreach ($menu_items as $item) {
          $menu_dictionary[$item->ID] = $item;
      }
      
      foreach ($menu_items as $item) {
         // if the item has no parent, it's a top level item
         if (empty($item->menu_item_parent)) {
            $top_menu_items[] = $item;
         }
         else {
            $parent = $menu_dictionary[$item->menu_item_parent];
            // if the item's parent has no parent, it's a 1st level item
            if (empty($parent->menu_item_parent)) {
               $sub1_menu_items[] = $item;
            }
            // it's a second level item
            else {
               $sub2_menu_items[] = $item;
            }
         }
      }
      
      $is_spanish = get_locale() == "es_MX";
      $home_url = $is_spanish ? "/es" : "/";

      $notice_text = null;
      $notice_url = null;
      if (is_front_page()) {
         if ($is_spanish) {
            $notice_text = get_option('notice-text-es');
            $notice_url = get_option('notice-url-es');
         } else {
            $notice_text = get_option('notice-text');
            $notice_url = get_option('notice-url');
         }
      }
      $show_notice = !empty($notice_text);

      ?>

      <div class="main-masthead-wrapper">
         <div class="main-masthead">
            <div class="container">
               <nav>
                  
                  <a class="header-logo" href="<?php echo $home_url; ?>">
                     <img height="40" src="<?php echo wp_get_attachment_url(get_option('header-logo-image-attachment-id')); ?>" alt="<?php echo get_bloginfo('name'); ?>"/>
                  </a>
                  
                  <div id="hamburger" class="header-hamburger">
                     <i class="fas fa-bars"></i>
                  </div>
                  
                  <ul id="main-menu" class="header-menu">
                     <?php foreach ($top_menu_items as $item):
                        
                        $id = get_page_id($item);
                        $page = get_page($id);
                        $url = $item->url;
                        $children = array_filter($sub1_menu_items, function($sub_item) use ($item) {
                           return $sub_item->menu_item_parent == $item->ID;
                        });
                        $children_ids = array_map('get_menu_item_id', $children);
                        $children_page_ids = array_map('get_page_id', $children);
                        $all_level_2_children = array_filter($sub2_menu_items, function($sub_item) use ($children_ids) {
                           return in_array($sub_item->menu_item_parent, $children_ids);
                        });
                        $all_level_2_children_page_ids = array_map('get_page_id', $all_level_2_children);
                        
                        $is_current = $queried_object_id != 0 &&
                           ($id == $queried_object_id
                           || in_array($queried_object_id, $children_page_ids)
                           || in_array($queried_object_id, $all_level_2_children_page_ids));
                        
                        if ($is_current) {
                           $current_sub1_menu_items = $children;
                        }

                        $is_donate = strtolower($item->title) == 'give' || strtolower($item->title) == 'donar';

                        ?>
                        
                        <li class="header-menu-item<?php
                                 echo ($is_current ? ' header-current-menu-item' : '');
                                 echo (!empty($children) ? ' has-children' : '');
                                 echo ($is_donate ? ' header-donate' : '');
                                 ?>">
                           <a href="<?php echo $url; ?>" <?php echo $is_donate ? 'target="_blank"' : '' ?>>
                              <?php echo $item->title; ?>
                           </a>
                           <?php if (!empty($children)) { ?>
                              <ul class="header-context-menu">
                                 <?php foreach ($children as $child) {
                                    $child_id = get_page_id($child);
                                    $child_url = $child->url;
                                    $level_2_children = array_filter($sub2_menu_items, function($sub_item) use ($child) { 
                                       return $sub_item->menu_item_parent == $child->ID;
                                    });
                                    $level_2_children_page_ids = array_map('get_page_id', $level_2_children);
                                    $is_current = in_array($queried_object_id, $level_2_children_page_ids);
                                    if ($is_current) {
                                       $current_sub1_menu_item_id = $child_id;
                                       $current_sub1_menu_items = $children;
                                       $current_sub2_menu_items = $level_2_children;
                                    }
                                    ?>
                                    <li class="header-context-menu-item<?php
                                       echo (!empty($level_2_children) ? ' has-children' : '');
                                    ?>">
                                       <a href="<?php echo $child_url; ?>">
                                          <?php echo $child->title; ?>
                                       </a>
                                       <?php if (!empty($level_2_children)) { ?>
                                          <ul class="header-context-menu header-2nd-context-menu">
                                             <?php foreach ($level_2_children as $level_2_child) {
                                                $level_2_child_id = get_page_id($level_2_child);
                                                $level_2_child_url = $level_2_child->url;
                                                ?>
                                                <li class="header-context-menu-item">
                                                   <a href="<?php echo $level_2_child_url; ?>">
                                                      <?php echo $level_2_child->title; ?>
                                                   </a>
                                                </li>
                                             <?php } ?>
                                          </ul>
                                       <?php } ?>
                                    </li>
                                 <?php } ?>
                              </ul>
                           <?php } ?>
                        </li>
                           
                     <?php endforeach; ?>
                  </ul>
                  
               </nav>
            </div>
         </div>
      </div>
      
      <?php function create_sub_menu_nav($items, $current_sub1_menu_item_id, $is_last, $is_second) { ?>
         <div class="sub-masthead-wrapper">
            <div class="sub-masthead<?php echo ($is_last ? ' last' : ' not-last'); echo ($is_second ? ' second' : ''); ?>">
               <div class="container">
                  <nav>
                     <ul class="header-menu">
                        <?php foreach ($items as $item):
                           $id = get_page_id($item);
                           $url = $item->url;
                           global $wp_query;
                           $is_current = $id == (int) $wp_query->queried_object_id || $id == $current_sub1_menu_item_id;
                           ?>
                           <li class="header-menu-item <?php echo ($is_current ? 'header-current-menu-item' : ''); ?>">
                              <a href="<?php echo $url; ?>">
                                 <?php echo $item->title; ?>
                              </a>
                           </li>
                        <?php endforeach; ?>
                     </ul>   
                  </nav>
               </div>
            </div>
         </div>
      <?php }
      
      $has_sub1_menu_nav = !empty($current_sub1_menu_items);
      $has_sub2_menu_nav = !empty($current_sub2_menu_items);
      
      // if ($has_sub1_menu_nav) {
      //    $is_last = !$has_sub2_menu_nav;
      //    $is_second = false;
      //    create_sub_menu_nav($current_sub1_menu_items, $current_sub1_menu_item_id, $is_last, $is_second);
      // }
      
      // if ($has_sub2_menu_nav) {
      //    $is_last = true;
      //    $is_second = true;
      //    create_sub_menu_nav($current_sub2_menu_items, null, $is_last, $is_second);
      // }
      
      ?>
      
      <!-- <div class="nav-divider <?php empty($current_sub1_menu_items) ? 'no-sub-header' : '' ?>"></div> -->
      
      <div class="<?php echo empty($current_sub1_menu_items) ? '' : 'has-double-header'; ?>">