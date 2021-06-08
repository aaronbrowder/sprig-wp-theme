document.addEventListener('DOMContentLoaded', contextMenu, false);
document.addEventListener('DOMContentLoaded', hamburgerMenu, false);

function hamburgerMenu() {

   var isMenuExpanded = false;
   
   var hamburgerId = 'hamburger';
   var mainMenuId = 'main-menu';
   var menuItemClassName = 'has-children';

   var hamburger = document.getElementById(hamburgerId);
   var mainMenu = document.getElementById(mainMenuId);
   var menuItems = mainMenu.getElementsByClassName(menuItemClassName);

   function collapseMenu() {
      isMenuExpanded = false;
      mainMenu.classList.remove('visible');
      for (var i = 0; i < menuItems.length; i++) {
         var menuItem = menuItems[i];
         menuItem.classList.remove('expanded');
      }
   }

   function hasAncestorWithId(el, id) {
      while (el) {
         if (el.id === id) return true;
         el = el.parentElement;
      }
      return false;
   }

   window.onresize = function() {
      collapseMenu();
   };

   document.addEventListener('click', function(event) {
      if (isMenuExpanded) {
         var isClickInsideMenu = !!hasAncestorWithId(event.target, mainMenuId);
         if (!isClickInsideMenu) {
            var isClickInsideButton = !!hasAncestorWithId(event.target, hamburgerId);
            if (!isClickInsideButton) collapseMenu();
         }
      }
   });

   hamburger.addEventListener('click', function(event) {
      isMenuExpanded = !isMenuExpanded;
      mainMenu.classList.toggle('visible');
      event.preventDefault();
   });

   for (var i = 0; i < menuItems.length; i++) {
      var menuItem = menuItems[i];
      registerMenuItemExpandEvent(menuItem);
   }

   function registerMenuItemExpandEvent(menuItem) {
      var link = menuItem.getElementsByTagName('a')[0];
      var subList = menuItem.getElementsByTagName('ul')[0];
      link.addEventListener('click', function(e) {
         // if this is not in the hamburger menu or there are no sub-menu-items,
         // we just want to follow the link like normal
         if (!isMenuExpanded || !subList) return true;
         menuItem.classList.toggle('expanded');
         e.preventDefault();
         return false;
      });
   }
}

function contextMenu() {
   
   var menuItems = document.getElementsByClassName('header-menu-item has-children');
   var subMenuItems = document.getElementsByClassName('header-context-menu-item has-children');
   
   for (var i = 0; i < menuItems.length; i++) {
      registerEvents(menuItems[i], function() {
         closeAllContextMenus(menuItems);
      });
   }
   
   for (var i = 0; i < subMenuItems.length; i++) {
      registerEvents(subMenuItems[i], function() {
         closeAllContextMenus(subMenuItems);
      });
   }

   function closeAllContextMenus(menuItems) {
      for (var i = 0; i < menuItems.length; i++) {
         var contextMenu = getContextMenu(menuItems[i]);
         if (contextMenu) {
            contextMenu.classList.remove('visible');
         }
      }
   }

   function registerEvents(menuItem, closeAllContextMenusCallback) {
      var contextMenu = getContextMenu(menuItem);
      if (contextMenu) {
         var isClosing = false;
         var hasPositionedSecondLevel = false;
         menuItem.addEventListener('mouseover', function() {
            closeAllContextMenusCallback();
            isClosing = false;
            contextMenu.classList.add('visible');
            if (!hasPositionedSecondLevel) {
               hasPositionedSecondLevel = true;
               positionSecondLevelContextMenus(contextMenu);
            }
         });
         menuItem.addEventListener('mouseleave', function() {
            isClosing = true;
            setTimeout(function() {
               if (isClosing) {
                  contextMenu.classList.remove('visible');
               }
            }, 50);
         });
         menuItem.addEventListener('click', function() {
            closeAllContextMenus(menuItems);
            closeAllContextMenus(subMenuItems);
         });
      }
   }
   
   function positionSecondLevelContextMenus(contextMenu) {
      var items = contextMenu.children;
      for (var i = 0; i < items.length; i++) {
         var item = items[i];
         var secondContextMenu = getContextMenu(item);
         if (secondContextMenu) {
            var positionInfo = item.getBoundingClientRect();
            secondContextMenu.style.left = positionInfo.width + 'px';
            secondContextMenu.style.top = '-8px';
         }
      }
   }

   function getContextMenu(menuItem) {
      return menuItem.children && menuItem.children.length > 1 ? menuItem.children[1] : null;
   }

}
