<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Google_Client;
use Google\Service\Oauth2;

class GoogleController extends Controller
{
    /**
     * Gets a Google client.
     *
     * @return Google_Client
     */
    private function getClient(): Google_Client
    {
        // Load our config.json that contains our credentials for accessing Google's API as a JSON string.
        $configJson = base_path() . '/config.json';

        // Define an application name.
        $applicationName = 'ogilvista';

        // Create the client.
        $client = new Google_Client();
        $client->setApplicationName($applicationName);
        $client->setAuthConfig($configJson);
        $client->setAccessType('offline'); // Necessary for getting the refresh token.
        $client->setApprovalPrompt('force'); // Necessary for getting the refresh token.

        // Scopes determine what Google endpoints we can access. Keep it simple for now.
        $client->setScopes([
            Oauth2::USERINFO_PROFILE,
            Oauth2::USERINFO_EMAIL,
            Oauth2::OPENID,
            // Oauth2::DRIVE_METADATA_READONLY // Allows reading of Google Drive metadata.
        ]);

        $client->setIncludeGrantedScopes(true);

        return $client;
    }

    /**
     * Return the URL of the Google auth.
     * Frontend @ Wilson should call this and then direct to this URL.
     *
     * @return JsonResponse
     */
    public function getAuthUrl(Request $request): JsonResponse
    {
        // Create Google client.
        $client = $this->getClient();

        // Generate the URL at Google we redirect to.
        $authUrl = $client->createAuthUrl();

        // HTTP 200.
        return response()->json($authUrl, 200);
    }

    // Google login.
    public function postLogin(Request $request): JsonResponse
    {
        // Get auth code from the query string. URL decode if necessary.
        $authCode = urldecode($request->input('auth_code'));

        // Google client.
        $client = $this->getClient();

        // Exchange auth code for access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Set the access token with Google. NB JSON.
        $client->setAccessToken(json_encode($accessToken));

        // Get user's data from Google.
        $service = new Oauth2($client);
        $userFromGoogle = $service->userinfo->get();

        // Select user if already exists.
        $user = User::where('provider_name', 'google')
            ->where('provider_id', $userFromGoogle->id)
            ->first();

        // Create or update the user.
        if (!$user) {
            $user = User::create([
                'provider_id' => $userFromGoogle->id,
                'provider_name' => 'google',
                'google_access_token_json' => json_encode($accessToken),
                'first_name' => $userFromGoogle->givenName,
                'last_name' => $userFromGoogle->familyName,
                'email' => $userFromGoogle->email,
            ]);
        } else {
            $user->update([
                'google_access_token_json' => json_encode($accessToken),
                'first_name' => $userFromGoogle->givenName,
                'last_name' => $userFromGoogle->familyName,
            ]);
        }

        // Log in and return token. HTTP 201.
        $token = $user->createToken("Google")->accessToken;

        $plainToken = $user->createToken("myapptoken")->plainTextToken;
        $response = [
            "user" => $user,
            "data" => $token,
            "token" => $plainToken
        ];

        // return response($response, 201);
        return response()->json($response, 201);
    }
}
