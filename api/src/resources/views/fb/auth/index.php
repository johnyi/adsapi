<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <title>Facebook Login</title>
    </head>
    <body>
        <script>
            function login() {
                FB.login(function (response) {
                    if (response.status === 'connected') {
                        const id = response['authResponse']['userID'];
                        const accessToken = response['authResponse']['accessToken'];

                        // Get user profile
                        FB.api('/me', {fields: 'id,email,name,first_name,last_name,picture,gender'}, function (response) {
                            const params = {id};

                            if (response.hasOwnProperty("email")) {
                                params['email'] = response['email'];
                            }

                            if (response.hasOwnProperty("name")) {
                                params['name'] = response['name'];
                            }

                            if (response.hasOwnProperty("first_name")) {
                                params['firstName'] = response['first_name'];
                            }

                            if (response.hasOwnProperty("last_name")) {
                                params['lastName'] = response['last_name'];
                            }

                            if (response.hasOwnProperty("picture")) {
                                params['picture'] = response['picture']['data']['url'];
                            }

                            if (response.hasOwnProperty("gender")) {
                                params['gender'] = response['gender'];
                            }

                            axios.post('/fb/auth/user', {
                                ...params
                            }).then(function (response) {
                                console.log(response);
                            }).catch(function (error) {
                                console.log(error);
                            });
                        });

                        // Exchange to long lived token
                        FB.api('/oauth/access_token', {
                            grant_type: 'fb_exchange_token',
                            client_id: '812353922569429',
                            client_secret: '6c9c189edc2906c3ceaae8b96a57aa53',
                            fb_exchange_token: accessToken,
                        }, function (response) {
                            let expiresAt = null;

                            if (response.hasOwnProperty("expires_in")) {
                                expiresAt = moment().add(response['expires_in'], 's').format('YYYY-MM-DD HH:mm:ss');
                            }

                            axios.post('/fb/auth/token', {
                                id,
                                accessToken: response['access_token'],
                                expiresAt,
                            }).then(function (response) {
                                console.log(response);
                            }).catch(function (error) {
                                console.log(error);
                            });
                        });
                    } else if (response.status === 'not_authorized') {
                    } else {
                    }
                }, {
                    scope: 'email,user_gender,manage_pages,pages_messaging,ads_management'
                });
            }

            window.fbAsyncInit = function () {
                FB.init({
                    appId: '812353922569429',
                    autoLogAppEvents: true,
                    xfbml: true,
                    version: 'v5.0'
                });
            };
        </script>
        <script async defer src="https://connect.facebook.net/en_US/sdk.js"></script>
        <div class="container">
            <div class="row justify-content-center my-5">
                <div class="mx-auto" style="width: 200px;">
                    <button type="button" class="btn btn-primary" onclick="login()">Login with Facebook</button>
                </div>
            </div>
        </div>
    </body>
</html>
