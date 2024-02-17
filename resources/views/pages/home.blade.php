@extends('layout.app')

@section('content')
    <div class="p-5">
        <h1 class="text-center font-weight-bolder text-9xl"><strong>Sanctum_Authentication</strong></h1>
        <a href="{{ url('/userLogin') }}" class="btn bg-gradient-primary d-flex justify-content-center">Welcome</a>
    </div>

    <div class="p-5 d-flex justify-content-center">
        <img src="{{ asset('images/Hafizur_Rahman_Shadhin.jpg') }}" alt="Hafizur Rahman Shadhin" class="img-fluid"
            style="max-width: 100%; height: auto;">
    </div>
@endsection
