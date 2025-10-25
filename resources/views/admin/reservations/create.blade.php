@extends('admin.layout')
@section('title','Nieuwe reservering – '.ucfirst($type))
@section('page_title','Nieuwe reservering – '.ucfirst($type))

@section('content')
  @include('admin.reservations.form', [
    'action' => route('admin.'.$type.'.store'),
    'method' => 'POST',
    'reservation' => $reservation,
    'type' => $type,
  ])
@endsection
