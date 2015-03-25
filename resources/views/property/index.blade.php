@extends('app')
@section('content')
<div class="container" ng-app="propertyApp">

    <div ng-controller="mainCtrl">
     <div ng-bind="infoMsg" class="alert-warning"></div>
      <div ng-view>

      </div>
    </div>

</div>
@endsection

