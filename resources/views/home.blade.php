@extends('layouts.app')

@section('content')
    <home-products
        :products='@json($products)'
        :is-auth='@json(auth()->check())'>
    </home-products>
@endsection
