<?php
  namespace App;
  use Everside\Assets;

  $phone = get_post_meta($post->ID, 'phone', true);
  $street = get_post_meta($post->ID, 'address_street', true);
  $city = get_post_meta($post->ID, 'address_city', true);
  $state = get_post_meta($post->ID, 'address_state', true);
  $zipcode = get_post_meta($post->ID, 'address_zipcode', true);

  $full_address = sprintf( '%s %s %s %s',
    $street ? $street : '',
    $city ? $city . ',' : '',
    $state ? $state : '',
    $zipcode ? $zipcode : ''
  );

?>
<header class="container-full-width wp-block wp-block-everside-hero-2column header-clinics-providers">
  <div class="container">
    <div class="row">
      <div class="col-md-5 content-image">
        <iframe
          class="clinic-gmap-embed"
          width="100%"
          height="100%"
          frameborder="0"
          style="border:0"
          src="https://www.google.com/maps/embed/v1/place?key={{ \Everside\GoogleMaps::getApiKey() }}&q={{ $full_address }}" allowfullscreen>
        </iframe>
      </div>
      <div class="col-md-7 content-area">
        <h1>{{ $title }}</h1>
        <address>
          <?php
            echo sprintf( '%s <p class="icons icon-location">%s %s %s %s</p>',
              $phone ? sprintf( '<p class="icons icon-phone"><a href="tel:%s">%s</a></p>', $phone, $phone ) : '',
              $street ? $street . '<br />' : '',
              $city ? $city . ',' : '',
              $state ? $state : '',
              $zipcode ? $zipcode : ''
            );
          ?>
        </address>

        <a href="{{ get_permalink( get_page_by_path( 'sign-in' ) ) }}#sign-in" class="btn btn-primary">Schedule Appointment</a>

      </div>
    </div>
  </div>
</header>
