@extends('layouts.app')
@section('content')
<approval_view-component :request = "{{ $request }}"></approval_view-component>
@endsection