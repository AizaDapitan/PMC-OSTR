@extends('layouts.app')
@section('content')
<mcd_view-component :request = "{{ $request }}"></mcd_view-component>
@endsection