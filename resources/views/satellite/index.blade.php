@extends('layouts.app')

@section('content')
<index_satellite-component :satellites="{{ $satellites }}"></index_satellite-component>
@endsection