@extends('layouts.app')
@section('content')
<mcd_edit-component :request = "{{ $request }}"></mcd_edit-component>
@endsection