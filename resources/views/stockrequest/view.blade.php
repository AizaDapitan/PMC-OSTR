@extends('layouts.app')
@section('content')
<stockrequest_view-component :request = "{{ $request }}"></stockrequest_view-component>
@endsection