@extends('layouts.master')

@section('title')
    Profile
@endsection
@section('content')

    <h2>Profile</h2>
    <div>
        <h3>Your personal data</h3>
        <p>Name: {{$user->name}}</p>
        <p>Email: {{$user->email}}</p>
    </div>

    <div>
        <h3>Your addresses</h3>
        @foreach($user->addresses as $address)
        <p>{{$address->postal_code}}</p>
        @endforeach

        <form method="post" action="/upload-csv" enctype="multipart/form-data">
            @csrf
            <label for="csv">Spreadsheet</label>

            <input id="csv" name="csv" type="file" accept=".csv"/>

            <div class="form-group">
                <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@endsection