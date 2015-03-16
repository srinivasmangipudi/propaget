@extends('app')
@section('content')
<div class="container" ng-app="propertyApp">
    <div ng-controller="propertyController">
            <h1>Property</h1>
            <div class="row">
                <div class="col-md-4">
                    <input type='text' ng-model="todo.title">
                    <button class="btn btn-primary btn-md"  ng-click="addTodo()">Add</button>
                    <i ng-show="loading" class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
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
                    <tr  ng-repeat='property in properties'>
                        <td>@{{ property.location }}</td>
                        <td>@{{ property.area }}</td>
                        <td>@{{ property.price }}</td>
                        <td>@{{ property.type }}</td>
                    </tr>
                </tbody>
            </table>

    </div>
</div>
@endsection

