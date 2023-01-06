@extends('layouts.app')
@section('content')
<stockrequest_index-component :delete = "{{ $delete }}"></stockrequest_index-component>
@endsection