@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"><h1>List Requirements</h1></div>
        </div>
        <div>
        @if ( !$allRequirements->count() )
                <h1>You have no projects</h1>
        @else
                <table class="table table-striped">
                <thead>
                    <tr class="info">
                        <td>Location</td>
                        <td>Area</td>
                        <td>Area Range</td>
                        <td>Price</td>
                        <td>Price Range</td>
                        <td>Type</td>
                    </tr>
                </thead>
                <tbody>
                @foreach( $allRequirements as $requirement )
                    <tr>
                        <td>{{ $requirement->location }}</td>
                        <td>{{ $requirement->area }}</td>
                        <td>{{ $requirement->range }}</td>
                        <td>{{ $requirement->price }}</td>
                        <td>{{ $requirement->priceRange }}</td>
                        <td>{{ $requirement->type }}</td>
                    </tr>
                @endforeach
                </tbody>
                </table>
        @endif
        </div>

    </div>

@endsection