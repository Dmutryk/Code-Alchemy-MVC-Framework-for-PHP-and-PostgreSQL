/**
 * Use this dependency declaration in login state of app.states.js
 */

deps: ['$ocLazyLoad', '$http', '$state','dataservice', function($ocLazyLoad,$http,$state,dataservice) {

    $http.get( dataservice.base_url()+ 'login-check', {

            withCredentials: true

        })

        .then(function (response) {

            // Not logged in?
            if (response.data.is_logged_in)

            // GO to login
                $state.go('restricted.dashboard');

        });

    return $ocLazyLoad.load([
        'lazy_iCheck',
        'app/components/pages/loginController.js'
    ]);



}]