<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')
  <body @php body_class() @endphp>
    <!-- Google Tag Manager (noscript) --><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K2HN5ZV"height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><!-- End Google Tag Manager (noscript) -->
    @php do_action('get_header') @endphp
    @include('partials.header')
    <main class="main" role="document">
      @yield('content')
    </main>
    @if (App\display_sidebar())
      <aside class="sidebar">
        @include('partials.sidebar')
      </aside>
    @endif
    @php do_action('get_footer') @endphp
    @include('layouts.footer')
    @php wp_footer() @endphp
  </body>
</html>
