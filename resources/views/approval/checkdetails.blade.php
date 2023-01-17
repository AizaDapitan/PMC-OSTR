@extends('layouts.details') 
@section('content')
<approval_checkdetails-component :request = "{{ $request }}"></approval_checkdetails-component>
@endsection