@extends('layouts.app')

@section('content')

  {!!
    Everside\Blocks::renderBlock('hero', [
      'bgColor' => '',
      'waveColor' => 'very-light-green',
    ], '
      <h1>Find a Health Center</h1>
      <h2>Everside Health providers handle 85 percent of member health needs and manage referrals for the rest</h2>
    ' )
  !!}


  @include('blocks.provider-clinics-cards', [
    'post_type' => 'clinic',
    'title' => 'Everside Has a Health Center Near You ',
    'paragraph' => sprintf( 'Our clean, calm and inviting health centers are conveniently located near your home or work—or even virtually. Find your closest health center. Or <a href="%s">schedule an appointment</a> online.', get_permalink( get_page_by_path( 'sign-in' ) ) . '#sign-in' ),
    'limit' => 9
  ])

@endsection
