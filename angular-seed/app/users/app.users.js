'use strict';

angular.module('myApp.users', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
      $routeProvider.when('/login', {
        templateUrl: 'users/views/login.html',
        controller: 'LoginCtrl'
      });
    }])

    .controller('LoginCtrl', ['$rootScope','$scope','$auth','$http','$location',function($rootScope,$scope,$auth,$http,$location) {

      $scope.credentials = {
        username: "",
        password: ""
      };

      $scope.loginError = false;
      $scope.loginErrorText = "";

      $scope.login = function() {

        $auth.login($scope.credentials).then(function(response) {

          if(response.data.success){

            $auth.setToken(response.data.data.access_token);
            $http.get('http://127.0.0.1/symfony-angular-rest-api/web/api/users/authenticated').success(function(response){
              var user = JSON.stringify(response.data);
              localStorage.setItem('user', user);
              $rootScope.currentUser = response.data;
              $location.path( "/home" );

            }).error(function(error){

            })
          }else{
            $scope.loginError = true;
            $scope.loginErrorText = response.data.errorMsg;
          }
        }).catch(function(response) {
          // Handle errors here, such as displaying a notification
          // for invalid email and/or password.
        });
      };

    }]);