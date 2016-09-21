LoginConnector
=====

This class provides integration of portal-webapp OAuth2 Login authentication and access to basic user information.


Requirements
-------------

* portal-webapp client credentials with sufficiant privileges (scope: `user_info_api`, grant_type: `authorization_code`, `refresh_token`)

* A working session (used for storing CSRF tokens)


Usage
-----

1. **Generate a login redirect link**
```php
$ggmLoginConnector = new LoginConnector(array(
    'portal_url' => 'http://my.portal-webapp.org',
    'client_id' => 'my-client-id',
    'secret' => 'my-client-secret'
));

// [...]
// At this point, your session should already be initialized

// Specify the URL of your endpoint that will handle the result of the login auth process
$loginUrl = $ggmLoginConnector->getRedirectLoginHelper()->getLoginUrl('http://my.website.org/my-redirect-endpoint');
```

You can have the link open either directly or in a popup window. Once the login / auth process is completed, the user will be forwarded to the endpoint you have specified when generating the URL.


2. **Fetch the access token**

Inside your redirect endpoint, you use the LoginConnector to retrieve the access token.

```php
// At this point, your session should already be initialized

// Make sure you specify exactly the same URL as you did when generating the link
$token = $ggmLoginConnector->getRedirectLoginHelper()->getAccessToken('http://my.website.org/my-redirect-endpoint');
```

It is recommended to save the full AccessToken instance for later use instead of requesting a new one repeatedly.


3. **Fetch user data**

The LoginConnector provides access to the `UserInfo` data node, which contains basic user information (id, name, email).

```php
// Note: This code assumes your token is valid and not expired (see further down)
$userInfo = $ggmLoginConnector->getUserInfo($token);
```

4. **Refreshing the AccessToken**

AccessTokens are only valid for about one hour, however it is possible to refresh them using the refresh token which is provided with every access token, stored in the AccessToken instance, and valid for roughly three months. It is recommended to check for token expiration before each request to the API.

```php
try {
    if ($token->isExpired()) {
        // Try to refresh the token
        $token = $ggmLoginConnector->refreshAccessToken($token);
    }
    // Use the token
    $userInfo = $ggmLoginConnector->getUserInfo($token);
} catch (AccessTokenExpiredException $ex) {
    // The refresh token has expired as well, it is
    // necessary to repeat the login / auth process
} catch (SDKException $ex) {
    // Something else went wrong, check the Exception message
}
```

