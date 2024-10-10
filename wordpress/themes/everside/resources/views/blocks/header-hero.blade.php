@php

@endphp

<section class="container-full-width wp-block wp-block-everside-hero -wave-bg {!! isset($class_names) ? $class_names : '' !!}" style="
  {!! isset($background_image) ? sprintf('background-image: url(%s);', $background_image) : '' !!}
  {!! isset($background_color) ? sprintf('background-color: %s;', $background_color) : '' !!}
">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 content-area fade-in-left">

        <h1>{{ $title }}</h1>
        <h2>{{ $subtitle }}</h2>
        {!! isset($content) && $content ? $content : '' !!}


      </div>
      <div class="col-sm-6 content-image">
        {{-- this is the mobile image--}}
        @if(isset($background_image_mobile))
          <img src="{{ $background_image_mobile }}" alt="" class="img-fluid"/>
        @endif
      </div>
    </div>
  </div>
</section>
