@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Tu componente recibe los datos como props --}}
    <dashboard-component
        :cursos="{{ json_encode($cursos) }}"
        :usuarios="{{ json_encode($usuarios) }}"
        :tipos="{{ json_encode($tipos) }}"
    ></dashboard-component>
@endsection