@extends('layouts.app')
@section('content')
<h1>Gêneros</h1>
@foreach ($generos as $genero)
  <p>{{ $genero->GNRNOME }}</p>
@endforeach
@endsection
