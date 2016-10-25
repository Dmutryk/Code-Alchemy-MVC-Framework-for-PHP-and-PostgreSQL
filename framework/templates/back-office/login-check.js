/**
 * Function to check if the User is currently logged in, via cross-domain session
 * @param {object} $state required to go to login page
 * @param {object} $http required to make login check call
 * @param {object} dataservice required to obtain base url
 *
 * Deployment: Add this function at the beginning of the function body in app.states.js
 */
function loginCheck($state,$http,dataservice){
    $http.get( dataservice.base_url()+ 'login-check', {
            withCredentials: true
        })
        .then(function (response) {
            // Not logged in?
            if  (! response.data.is_logged_in)
            // GO to login
                $state.go( 'login');
        });
}
