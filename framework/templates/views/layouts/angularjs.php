
<!DOCTYPE html>
<html ng-app="nameApp">
<head>
    <meta charset="utf-8">
    <title>Angular.js Example</title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.1/angular.min.js"></script>
    <script>
        var nameApp = angular.module('nameApp', []);
        nameApp.controller('NameCtrl', function ($scope){
            $scope.firstName = 'John';
            $scope.$watch('lastName', function(newValue, oldValue){
                console.log('new value is ' + newValue);
            });
            setTimeout(function(){
                $scope.lastName = 'Smith';
                $scope.$apply();
            }, 1000);
        });
    </script>
</head>
<body ng-controller="NameCtrl">
First name:<input ng-model="firstName" type="text"/>
<br>
Last name:<input ng-model="lastName" type="text"/>
<br>
Hello {{firstName}} {{lastName}}
</body>
