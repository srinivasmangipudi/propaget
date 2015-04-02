@extends('app')
@section('content')
<div class="container" ng-app="propertyApp">

    <div ng-controller="mainCtrl">
     <div ng-if="infoMsg" class="alert">
         <div class="alert alert-warning" ng-repeat="imsg in infoMsg track by $index">@{{ imsg }}</div>
     </div>
     <div ng-view>

     </div>
    </div>

</div>
@endsection

