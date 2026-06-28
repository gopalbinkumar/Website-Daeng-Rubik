@extends('errors.layout')

@section('title', 'Too Many Requests - 429')
@section('code', '429')
@section('heading', 'Too Many Requests')

@section('message')
    You are sending too many requests in a short amount of time. Please try again later.
@endsection