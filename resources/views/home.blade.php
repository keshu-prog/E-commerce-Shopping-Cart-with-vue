@extends('layouts.app')

@section('content')
    <home-products
        :products='@json($products)'
        :cart='@json($cart)'
        :is-auth='@json(auth()->check())'>
    </home-products>
@endsection
