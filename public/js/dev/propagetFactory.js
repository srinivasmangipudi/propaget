/**
 * Created by urmila on 26/3/15.
 */

var propagateServiceModule= angular.module('propagateServiceModule', []);

/** FACTORY METHOD STARTS **/
propagateServiceModule.factory('propagateService', ['$http', '$rootScope', function($http, $rootScope) {
    return {
        apiCall: function (operation, method, functionUrl, propagateData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + functionUrl + '?access_token=1baef17f40a43c182de78d67b0a01d5cfdd64732',
                method: method,
                data: (propagateData!=undefined) ? $.param(propagateData) : ''
            })
            .success(function (jsonData) {
                console.log('SUCESS=:=', jsonData);
            })
            .error(function(data, status, headers, config) {
                console.log('ERROR=:=', data);
            });
        }
    }
}]);