<footer id="footer">
  <div class="container">
    <nav class="row footer-widgets footer-contact">
      @php dynamic_sidebar('sidebar-footer-contact') @endphp
    </nav>

    <nav class="row footer-widgets footer-menus">
      @php dynamic_sidebar('sidebar-footer-menus') @endphp
    </nav>
  </div>

  <nav class="footer-widgets footer-utility">
    <div class="container">
      <div class="row">
        <div class="col-12 footer-utility-legal-disclaimer">
          @php dynamic_sidebar('sidebar-footer-legal-disclaimer') @endphp
        </div>
      </div>
      <div class="row">

        <div class="col-md-6 footer-utility-copyright">
          <?php echo sprintf( "&copy;%d - %s | All rights reserved", date('Y'), get_bloginfo( 'name' ) ); ?>
        </div>
        <div class="col-md-6 footer-utility-menu">
          <?php
          if(has_nav_menu('footer_utility')) {
            echo wp_nav_menu([
              'container' => false,
              'theme_location' => 'footer_utility',
              'menu_class' => 'nav',
            ]);
          }
          ?>
        </div>

      </div>
    </div>
  </nav>


</footer>



