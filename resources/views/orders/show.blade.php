@extends('layouts.app')

@section('content')
    <order-page :order='@json($order)' :is-auth='@json(auth()->check())'></order-page>
@endsection
