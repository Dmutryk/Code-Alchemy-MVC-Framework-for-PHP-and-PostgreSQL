angular
    .module('altairApp')
    .controller('loginCtrl', [
        '$scope',
        '$rootScope',
        'utils','dataservice','$http','toastr','$state',
        function ($scope,$rootScope,utils,dataservice,$http,toastr,$state) {

            $scope.registerFormActive = false;

            var $login_card = $('#login_card'),
                $login_form = $('#login_form'),
                $login_help = $('#login_help'),
                $register_form = $('#register_form'),
                $login_password_reset = $('#login_password_reset');

            // show login form (hide other forms)
            var login_form_show = function() {
                $login_form
                    .show()
                    .siblings()
                    .hide();
            };

            // show register form (hide other forms)
            var register_form_show = function() {
                $register_form
                    .show()
                    .siblings()
                    .hide();
            };

            // show login help (hide other forms)
            var login_help_show = function() {
                $login_help
                    .show()
                    .siblings()
                    .hide();
            };

            // show password reset form (hide other forms)
            var password_reset_show = function() {
                $login_password_reset
                    .show()
                    .siblings()
                    .hide();
            };

            $scope.loginHelp = function($event) {
                $event.preventDefault();
                utils.card_show_hide($login_card,undefined,login_help_show,undefined);
            };

            $scope.backToLogin = function($event) {
                $event.preventDefault();
                $scope.registerFormActive = false;
                utils.card_show_hide($login_card,undefined,login_form_show,undefined);
            };

            $scope.registerForm = function($event) {
                $event.preventDefault();
                $scope.registerFormActive = true;
                utils.card_show_hide($login_card,undefined,register_form_show,undefined);
            };

            $scope.passwordReset = function($event) {
                $event.preventDefault();
                utils.card_show_hide($login_card,undefined,password_reset_show,undefined);
            };

            // Credentials
            $scope.credentials = {
                email: '',
                password: '',
                remember_me: true
            };

            // Sign in
            $scope.signin = function($event){
                $event.preventDefault();
                $http.post(dataservice.base_url()+'do-signin',$scope.credentials)

                    .then(function(response){

                        if ( response.data.result ){

                            toastr.success('You are now signed in','Success');

                            $state.go('restricted.dashboard');
                        } else

                            toastr.error(response.data.error,'Unsuccessful Signin',{

                                preventDuplicates: true

                            });

                    });

            };

            // Register
            $scope.register = function($event){
                $event.preventDefault();
                // Clear errors
                $.each( $scope.inputErrors, function(index,input){
                   $scope.inputErrors[index] = '';
                });
                $http.post( dataservice.base_url()+ 'register-user',$scope.newUser,{

                    withCredentials: true

                })

                    .then(function(response){
                        var json = response.data;
                        if ( typeof(json.error)!='undefined'){

                            toastr.error(json.error,'Unsuccessful Signup',{
                                preventDuplicates: true,
                                timeOut: 8000
                            });

                            // Show errors
                            $.each(json.missing_fields,function(index,field){

                                $scope.inputErrors[field] = 'md-input-danger';

                            });
                        } else {

                            // congratulations
                            toastr.success("Your User Account has been created",'Successful Signup',{
                                preventDuplicates: true,
                                timeOut: 8000
                            });

                            // If logged in, redirect
                            if ( json.is_logged_in )

                                $state.go('restricted.dashboard');

                        }

                    });
            };

            // Register variables
            $scope.newUser = {
                first_name: '',
                last_name: '',
                email: '',
                password: ''
            };

            // Error classes
            $scope.inputErrors = {
                first_name: '',
                last_name: '',
                email: '',
                password: ''
            };

        }
    ]);