@extends('layouts.app')
@section('content')
    <div class="container my-5">
        <div class="mb-3 d-flex">
            <h1 class="mr-3">Characters</h1>
            <span class="text-secondary"> Showing {{ $limit }} of {{ $total }}</span>
        </div>
        <hr>
        @if($characters)
            @foreach ($characters as $character)
                <div class="d-flex flex-column flex-md-row">
                    <div class="content-div">
                        <a href="{{ route('characters.show', ['id'=>$character['id']]) }}" class="text-dark">
                            <img src="{{ $character['image'] }}" class="card-img-top" alt="{{ $character['name'] }}">
                        </a>
                    </div>
                    <div class="content-hr d-none d-md-block">
                        <hr class="hr-vertical2">
                        <hr class="hr-vertical1">
                    </div>
                    <div class="w-100 pl-md-3 mt-4 mt-md-0">
                        <a href="{{ route('characters.show', ['id'=>$character['id']]) }}"
                           class="text-dark text-decoration-none">
                            <h4>{{ $character['name'] }}</h4>
                        </a>
                        <p class="card-text">{{ $character['description'] }}</p>
                        <p class="text-secondary">
                            Modified &nbsp;<span class="ml-2"> {{ $character['modified'] }}</span>
                        </p>
                    </div>
                </div>
                <hr>
            @endforeach
        @endif
        <div class="w-100 mt-5 text-center overflow-auto">
            {{ $paginate }}
        </div>
    </div>
@endsection
