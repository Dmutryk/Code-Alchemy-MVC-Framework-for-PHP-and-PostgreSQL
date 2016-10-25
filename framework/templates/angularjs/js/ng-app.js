/**
 * AngularJS Application for __namespace__
 */
var __angular_appname__ = angular.module('__angular_appname__',
    ['ngRoute','__angular_app_controllers__','__angular_factory__']);

// Set up routing
__angular_appname__.config(function($routeProvider) {
    $routeProvider.
        when('/', {
            templateUrl: '/templates/services.hbs',
            controller: 'ServicesCtrl'
        }).
        when('/:serviceName', {
            templateUrl: '/templates/service-list.hbs',
            controller: 'ServiceListCtrl'
        }).
        otherwise({
            redirectTo: '/'
        });
});

