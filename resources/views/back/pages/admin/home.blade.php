@extends('back.layout.page-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')
 {{ Auth::guard('admin')->user()->email }}
@endsection
