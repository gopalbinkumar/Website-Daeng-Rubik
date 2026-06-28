@extends('errors.layout')

@section('title', 'Internal Server Error - 500')
@section('code', '500')
@section('heading', 'Internal Server Error')

@section('message')
    Sorry, an error occurred on the server. Please try again later.
@endsection