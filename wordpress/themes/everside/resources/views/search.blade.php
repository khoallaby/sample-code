@php
  global $wp_query;
@endphp
@extends('layouts.app')

@section('content')



  {!!
    Everside\Blocks::renderBlock('hero', [
      'bgColor' => '',
      'waveColor' => 'very-light-green',
    ], '
      <h1>Search</h1>
    ' )
  !!}


  <section class="wp-block wp-block-everside-search -wave-bg -extended-wave-bg background--very-light-green">
    <div class="container">
      <section class="search-form">
        <h2>You asked:</h2>
        {!! get_search_form(false) !!}
      </section>
      <section class="search-results-container">
        <h2>Your Results (<span class="search-total-results">{{ $wp_query->found_posts }}</span>)</h2>

        <section class="search-results">
          @while(have_posts()) @php the_post() @endphp
            <article @php post_class() @endphp>
              <header>
                <h3 class="entry-title"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h3>
              </header>
              <div class="entry-summary">
                @php the_excerpt() @endphp
              </div>
            </article>

            @endwhile
        </section>
      </section>

      <section class="pagination-container tui-pagination">
      </section>


    </div>
  </section>
@endsection
