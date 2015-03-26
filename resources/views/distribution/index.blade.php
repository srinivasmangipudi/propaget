@extends('app')
@section('content')
<div class="container" ng-app="distributionApp">
     <div ng-controller="mainCtrl">
        <div ng-bind="infoMsg" class="alert-warning">i m here</div>
        <div ng-view>

        </div>
     </div>
</div>
@endsection

