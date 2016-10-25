angular
    .module('altairApp')
    .controller('logoutCtrl', [
        '$scope',
        'dataservice','$http','toastr','$state',
        function ($scope,dataservice,$http,toastr,$state) {

            $http.post(dataservice.base_url()+'do-logout')

                .then(function(response){

                    $http.post(dataservice.base_url()+ 'login-check')

                        .then(function(response){

                            if ( ! response.data.is_logged_in )

                                $state.go('login');


                        })
                });

        }
    ]);