@extends('app')
@section('content')
<div class="container" ng-app="propertyApp">

    <div ng-controller="mainCtrl">
     <div ng-if="infoMsg" ng-bind="infoMsg" class="alert alert-warning"></div>
      <div ng-view>

      </div>
    </div>

</div>
@endsection

