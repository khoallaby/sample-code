@extends('layouts.app')

@section('content')
  <section class="container-full-width wp-block wp-block-everside-wrapper centered has-background background--very-light-green -wave-bg -extended-wave-bg">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h1>Sorry, we couldn't find that page for you.</h1>
          <p>
            But we're still here by your side and ready to help.<br />
            Visit our <a href="{{ home_url() }}">homepage</a> to try again or <a href="{{ get_permalink( get_page_by_path( 'contact-us' ) ) }}">contact us</a> and we'll make sure you find the info you need.
          </p>
        </div>
      </div>
    </div>
  </section>
@endsection
