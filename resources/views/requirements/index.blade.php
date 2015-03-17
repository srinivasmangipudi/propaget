@extends('app')
@section('content')
<div class="container" ng-app="requirementApp">
    <div ng-controller="requirementController">
            <h1>Requirement</h1>

            <hr>

            <table class="table table-striped">
                <thead>
                    <tr class="info">
                        <td>Location</td>
                        <td>Area</td>
                        <td>Price</td>
                        <td>Type</td>
                        <td>Edit</td>
                    </tr>
                </thead>
                <tbody>
                    <tr  ng-repeat='req in requirements'>
                        <td>@{{ req.location }}</td>
                        <td>@{{ req.area }}</td>
                        <td>@{{ req.price }}</td>
                        <td>@{{ req.type }}</td>
                    </tr>
                </tbody>
            </table>

    </div>
</div>
@endsection

