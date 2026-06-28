@extends('errors.layout')

@section('title', 'Service Unavailable - 503')
@section('code', '503')
@section('heading', 'Service Unavailable')

@section('message')
    The website is currently unavailable due to maintenance or temporary issues. Please try again later.
@endsection