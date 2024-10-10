@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp

    @php
      $categories = get_the_terms( null, 'category' );
      $category_slugs = wp_list_pluck( $categories, 'slug' );
      $is_bios = in_array( 'bios', $category_slugs );
      $gated = get_post_meta($post->ID, 'gated_content', true );
      $canAccess = isset($_COOKIE['gated-content']) ? $_COOKIE['gated-content'] : false;
    @endphp

    @if( $is_bios )
      @include( 'single-post-bios' )
    @else
      @if( $gated && !$canAccess )
        @include( 'blocks.form-gated' )
      @endif

      <article @php post_class() @endphp>
        @if( has_post_thumbnail() )
          <header class="hero-post">
            <figure>
              {!! get_the_post_thumbnail($post->ID, 'full') !!}
            </figure>
          </header>
        @endif
        <section class="container">
          <h1 class="entry-title">{!! get_the_title() !!}</h1>
          {{--@include('partials/entry-meta')--}}
          <div class="entry-content">
            @if( !$gated || $gated && $canAccess )
              @php the_content() @endphp
            @endif
          </div>
        </section>
      </article>
    @endif

  @endwhile
@endsection
