@extends('layouts.app')
@section('content')
    <edit_satellite-component :satellite = "{{ json_encode($satellite) }}"></edit_satellite-component>
@endsection
