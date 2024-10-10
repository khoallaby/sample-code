// a11y menu https://codepen.io/shadeed/pen/c0376e254f5343afc0211935eea16e16


export default class Menu {
  constructor() {
    this.responsiveWidth = '992';

    this.menu = document.querySelector('#menu-primary-menu');
    this.menuItems = document.querySelectorAll('#menu-primary-menu > li');
    this.menuButton = document.querySelector('#mobile-menu-toggle');

    this.searchContainer = document.querySelector('.header-search');
    this.searchButton = this.searchContainer.querySelector('.icon-search');
    this.searchForm = this.searchContainer.querySelector('.search-form');

    this.documentClick();
    this.escKeyPress();
    this.mobileButtonClick();
    this.openSubmenuEvents();
    this.searchButtonClick();
  }


  // close menus/search when clicking outside of them, on the document
  documentClick() {
    document.body.addEventListener('click', (e) => {

      // close the mobile menu
      if( window.innerWidth < this.responsiveWidth ) {
        // if we are clicking outside the mobile nav, and mobile btn. while the mobile menu is open
        if( !e.target.closest('.nav') &&
          e.target.parentNode !== this.menuButton &&
          this.menuButton.classList.contains('is-active')
        ) {
          this.toggleMobileMenu();
        }
      }


      // dont hide submenus if we're clicking on a menu link
      if( !e.target.parentNode.classList.contains('menu-item-has-children') ) {
        this.hideSubMenus();
      }

      // close search
      if( e.target !== this.searchButton &&
        e.target !== this.searchForm.querySelector('input')
      ) {
        this.closeSearchContainer();
      }

    }, true);
  }

  // a11y - esc key closes sub menu/search
  escKeyPress() {
    window.onkeyup = (e) => {
      if( e.keyCode === 27 ) {
        this.hideSubMenus();

        // hide mobile menu
        this.menuButton.classList.remove('is-active');
        this.menu.classList.remove('is-active');
        this.menuButton.setAttribute('aria-expanded', 'false')


        // close search
        this.closeSearchContainer();
      }
    }
  }

  mobileButtonClick() {
    this.menuButton.addEventListener('click', (e) => {
      this.toggleMobileMenu();


      // if search is open, close it
      if( this.searchForm.classList.contains('is-open') ) {
        this.toggleSearchForm();
      }
    });
  }

  toggleMobileMenu() {
    this.menuButton.classList.toggle('is-active');
    this.menu.classList.toggle('is-active');
    this.toggleAriaExpanded(this.menuButton);
  }


  // opens the sub menu
  openSubmenuEvents() {
    this.menuItems.forEach( (menuItem, index) => {
      menuItem.querySelector('a').addEventListener('click', (e) => {
        this.openSubMenus(e, menuItem, index);
      });

      // open submenu on hover
      menuItem.querySelector('a').addEventListener('mouseover', (e) => {
        // open the submenu if it isn't already open (because we're hovering over the top level link)
        if( !e.target.parentNode.classList.contains('is-open') ) {
          this.openSubMenus(e, menuItem, index);
        }
      });

      // hide submenu when leaving it
      menuItem.addEventListener('mouseleave', (e) => {
        if( window.innerWidth < this.responsiveWidth ) {
          return;
        }

        this.hideSubMenus();
      });
    });
  }


  openSubMenus(e, menuItem, index) {
    // ignore the hover events on mobile
    if( e.type === 'mouseover' && window.innerWidth < this.responsiveWidth ) {
      return;
    }

    // preventDefault when top menu item, with no link
    if( e.target.getAttribute('href') === '#' ) {
      e.preventDefault();
    }

    this.hideSubMenus(e.target.parentNode);

    // open the child submenu
    const subMenu = menuItem.querySelector('.sub-menu');

    if( subMenu ) {
      // add triangle class if the first submenu is clicked
      if( index === 0 ) {
        this.menuButton.classList.toggle('click-first-menu');
      }

      if( menuItem.classList.contains('is-open') ) {
        menuItem.classList.remove('is-open');
      } else {
        menuItem.classList.add('is-open');
      }

      //menuItem.classList.toggle('is-open');
      this.toggleAriaExpanded(e.target);
    }
  }


  hideSubMenus(exclude) {
    // hide all sub menus first
    this.menu.querySelectorAll('.menu-item-has-children').forEach( (menuItem, index) => {
      if( exclude !== menuItem ) {
        menuItem.classList.remove('is-open');
        menuItem.querySelector('a').setAttribute('aria-expanded', 'false');

        // remove the triangle class if first submenu is clicked
        if( index === 0 ) {
          this.menuButton.classList.remove('click-first-menu');
        }
      }
    } );
  }


  toggleAriaExpanded(el) {
    const expanded = el.getAttribute('aria-expanded');

    if( expanded === 'true' ) {
      el.setAttribute('aria-expanded', 'false')
    } else {
      el.setAttribute('aria-expanded', 'true')
    }
  }



  closeSearchContainer() {
    if( this.searchButton.classList.contains('is-open') ) {
      this.toggleSearchForm();
    }
  }


  searchButtonClick() {
    this.searchButton.addEventListener('click', (e) => {
      e.preventDefault();
      this.toggleSearchForm();

      // if mobile menu open, close it
      if( this.menuButton.classList.contains('is-active') ) {
        this.toggleMobileMenu();
      }
    });
  }

  toggleSearchForm() {
    this.searchButton.classList.toggle('is-open');
    this.searchForm.classList.toggle('is-open');
    this.toggleAriaExpanded(this.searchButton);
  }


}
