'use strict';

// Declare app level module which depends on views, and components
angular.module('myApp', [
  'ngRoute',
  'myApp.users',
  'myApp.home',
  'satellizer'
]).
config(['$locationProvider', '$routeProvider','$authProvider', function($locationProvider, $routeProvider,$authProvider) {
  $authProvider.loginUrl = 'http://127.0.0.1/symfony-angular-rest-api/web/api/users/login_checks';
  $locationProvider.hashPrefix('!');
  $routeProvider.otherwise({redirectTo: '/login'});
}]).run(function ($rootScope, $auth,$location) {

  $rootScope.isAuthenticated = function() {
    return $auth.isAuthenticated();
  };

  $rootScope.logout = function() {
    $auth.logout().then(function() {
      localStorage.removeItem('user');
      $rootScope.currentUser = null;
      $location.path( "/login" );
    });
  };
  $rootScope.currentUser = JSON.parse(localStorage.getItem('user'));
});

