@extends('layouts.app')
@section('content')
    <div class="container my-4">
        @if($character)
            <div class="d-flex justify-content-between">
                <h4>{{ $character['name'] }}</h4>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm float-right mb-3"
                   type="button">Back</a>
            </div>
            <img src="{{ $character['image'] }}" class="w-100 h-auto" alt="{{ $character['name'] }}">
            <div class="w-100 mt-3">
                <p class="card-text">{{ $character['description'] }}</p>
                <p class="text-secondary">
                    Modified &nbsp;<span class="ml-2"> {{ $character['modified'] }}</span>
                </p>
            </div>
        @endif
    </div>
@endsection
