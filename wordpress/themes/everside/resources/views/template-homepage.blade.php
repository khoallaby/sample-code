{{--
  Template Name: Home Page Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    {{--@include('partials.page-header')--}}

    @include('blocks.header-hero', [
        'title' => 'Your Partner in Better Health',
        'subtitle' => 'By your side for every step of your health journey',
        'content' => 'REDACTED',
        'background_color' => '#eae9ee',
        'background_image' => '/wp-content/themes/paladina/dist/images/hero-homepage.png',
        'background_image_mobile' => '/wp-content/themes/paladina/dist/images/hero-homepage-mobile.png',
    ])
    @include('blocks.homepage-wrapper')
    @include('blocks.mosaic')
    @include('blocks.find-health-center')
    @include('blocks.card__2col-image', [
        'background' => 'very-light-green',
        'image' => '/wp-content/themes/paladina/dist/images/uploads/image-doctor3.png',
        'title' => 'The Care Team You Deserve',
        'subtitle' => 'Top providers with time to focus on patients',
        'body' => '
          REDACTED
        ',
        'offset_image' => true,
        'image_cropped' => true,
        'reverse' => false,
        'column1' => 6,
        'column2' => 6,
    ])
    @include('blocks.homepage-wrapper2')
    @include('blocks.homepage-cards')
    @include('blocks.homepage-cards-circle')
    @include('blocks.form--start-journey')

    @include('partials.content-page')

  @endwhile
@endsection
