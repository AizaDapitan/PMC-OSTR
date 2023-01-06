@extends('layouts.app')
@section('content')
<stockrequest_unsaved-component :delete = "{{ $delete }}"></stockrequest_unsaved-component>
@endsection