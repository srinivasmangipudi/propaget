/**
 * Created by urmila on 26/3/15.
 */

var propagateServiceModule= angular.module('propagateServiceModule', ['ngCookies']);

/** FACTORY METHOD STARTS **/
propagateServiceModule.factory('propagateService', ['$http', '$rootScope', '$cookieStore', function($http, $rootScope, $cookieStore) {
    var access_token = readCookie('access_token');
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


function readCookie(name) {
    var cookiename = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++)
    {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(cookiename) == 0) return c.substring(cookiename.length,c.length);
    }
    return null;
}