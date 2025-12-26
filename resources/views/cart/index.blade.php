@extends('layouts.app')

@section('content')
    <cart-page :cart='@json($cart)' :is-auth='@json(auth()->check())'></cart-page>
</div>
@endsection
