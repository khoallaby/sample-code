@php
  wp_enqueue_script( 'everside-card-form-recaptcha' );
  wp_enqueue_script( 'everside-card-form' );
@endphp
@extends('layouts.app')

@section('content')
  @include('blocks.header-category')

  @include('blocks.cards-category')


  @include('blocks.cards__social-media')


  {!!
    Everside\Blocks::renderBlock('card-form', [
      'bgColor' => 'very-light-purple',
      'formType' => 'me',
      'title' => 'Start Your Journey to Better Healthcare',
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

