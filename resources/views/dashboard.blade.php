@extends('layouts.app')

@section('content')
     <dashboard-page
        :orders='@json($orders)'
        :addresses='@json($addresses)'></dashboard-page>
    
@endsection
