<?php

return array(
    
	/*
    |--------------------------------------------------------------------------
    | Google oauth2_client_id
    |--------------------------------------------------------------------------
    |
    | Visit https://code.google.com/apis/console?api=plus to generate your
    | oauth2_client_id, oauth2_client_secret, and to register your
    | oauth2_redirect_uri.
    |
    | Should be '*.apps.googleusercontent.com'
    |
    */
    'clientId' => '*.apps.googleusercontent.com',

    /*
    |--------------------------------------------------------------------------
    | Google oauth2_client_secret
    |--------------------------------------------------------------------------
    */
    'clientSecret' => null,

    /*
    |--------------------------------------------------------------------------
    | Google oauth2_redirect_uri
    |--------------------------------------------------------------------------
    |
    | The default, URL::to('/'), will usually suffice - redirects the user to
    | your home page after a successful login
    |
    */
    'redirectUri' => URL:to('/v1/oauths/google'), // URL:to('/') doesn't include the trailing slash

	/*
	|--------------------------------------------------------------------------
	| Service Account Name
	|--------------------------------------------------------------------------
	|
	| Set your service account name, it should look like:
	| xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx@developer.gserviceaccount.com
	|
	*/
	
	'service_email' => null,


	/*
	|--------------------------------------------------------------------------
	| Path to the .p12 certificate
	|--------------------------------------------------------------------------
	|
	| You need to download this from the Google API Console when the
	| service account was created.
	|
	| Make sure you keep your key.p12 file in a secure location, and isn't
	| readable by others.
	|
	*/

	'certificate_path' => __DIR__ . '/key.p12',


	/*
	|--------------------------------------------------------------------------
	| Returns objects
	|--------------------------------------------------------------------------
	|
	| Returns objects of the Google API Service instead of associative arrays
	|
	*/

	'use_objects' => true,
	
    /*
    |--------------------------------------------------------------------------
    | Google Developer Key
    |--------------------------------------------------------------------------
    */
    'developerKey' => null,

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | The Application Name to register with the Google API.
    |
    */
    'applicationName' => null,
    
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | An array of scopes to be requested during authentication.
    | For information about available login scopes, see
    | https://developers.google.com/+/api/oauth#login-scopes.
    | To see the available scopes for all Google APIs, visit the
    | APIs Explorer at https://developers.google.com/apis-explorer/#p/ .
    |
    */
    'scopes' => array(
        'https://www.googleapis.com/auth/userinfo.profile',
        'https://www.googleapis.com/auth/userinfo.email',
    ),
    
    /*
    |--------------------------------------------------------------------------
    | access_type
    |--------------------------------------------------------------------------
    |
    | The effect of this property is documented at
    | https://developers.google.com/accounts/docs/OAuth2WebServer#offline;
    | if an access token is being requested, the client does not receive
    | a refresh token unless offline is specified.
    | Possible values for access_type include:
    | "offline" to request offline access from the user. (This is the default value)
    | "online" to request online access from the user.
    |
    */
    'access_type' => 'offline',

);
