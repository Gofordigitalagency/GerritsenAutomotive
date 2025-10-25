@extends('admin.layout')
@section('title','Reservering bewerken – '.ucfirst($type))
@section('page_title','Reservering bewerken – '.ucfirst($type))

@section('content')
  @include('admin.reservations.form', [
    'action' => route('admin.'.$type.'.update', $reservation),
    'method' => 'PUT',
    'reservation' => $reservation,
    'type' => $type,
  ])
@endsection
