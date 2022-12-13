@extends('layouts.app')
@section('content')
<stockrequest_dashboard-component :user = "{{ Auth::user() }}"></stockrequest_dashboard-component>
@endsection