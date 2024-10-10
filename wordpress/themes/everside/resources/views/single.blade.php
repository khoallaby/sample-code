@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <section class="container">
      @include('partials.content-single-'.get_post_type())
    </section>
  @endwhile
@endsection
