<?php
use App\Testing;
$testing = new Testing();

wp_enqueue_script( 'everside-card-form-recaptcha' );
wp_enqueue_script( 'everside-card-form' );
?>

@extends('layouts.app')

@section('content')

  @if( $alert_content = get_post_meta( $post->ID, 'alert_content', true ) )
    @include('blocks.alert', [
      'content' => $alert_content,
    ])
  @endif


  @include('blocks.header-single-clinic', [
    'title' => get_the_title($post->ID),
  ])



  @include('blocks.provider-clinics-cards-single', [
    'title' => 'Providers',
    'paragraph' => 'Please note that sponsoring organizations determine which providers and health centers their members can visit.',
    'limit' => 3
  ])


  {!!
    Everside\Blocks::renderBlock('card-video', [
      'bgColor' => '',
      'waveBg' => false,
      'waveStyle' => '',
    ], '
    <div class="wp-block-columns">
      <div class="wp-block-column col-content" style="flex-basis:40%">
        <h2>Your Health Is Our Top Priority</h2>
        <h3>Hear From Everside Providers</h3>
        <p>Watch Everside Health providers from across the country share the ways our relationship-based healthcare model puts people first.</p>
      </div>
      <div class="wp-block-column col-video" style="flex-basis:60%">
        <figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio">
          <div class="wp-block-embed__wrapper">
            <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/f2lJ3fLv6tg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        </figure>
      </div>
    </div>
    ' )
  !!}


  {!!
    Everside\Blocks::renderBlock('card-form', [
      'bgColor' => 'very-light-purple',
      'formType' => 'sales',
      'title' => 'Questions? Let us help',
      'waveBg' => true,
      'waveStyle' => 'extended',
    ], '
        <h4>Reach out to learn how Everside Health can work for you</h4>
        <p>Our direct primary care helps:</p>
        <ul>
          <li>Members enjoy better health with low—to no—costs</li>
          <li>Organizations reduce healthcare spending</li>
          <li>Providers focus on patients </li>
        </ul>
        <p>We look forward to hearing from you.</p>
    ' )
  !!}


@endsection
