<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\User;
use Facebook\Facebook as FB;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function js()
    {
        return view('fb.auth.index');
    }

    public function user(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $userId = $request->input('id');

        $user = User::where('user_id', '=', $userId)->first();
        if (empty($user)) {
            $user = new User();
            $user['user_id'] = $userId;
        }

        $user['email'] = $request->input('email') ?: null;
        $user['name'] = $request->input('name') ?: null;
        $user['first_name'] = $request->input('firstName') ?: null;
        $user['last_name'] = $request->input('lastName') ?: null;
        $user['picture'] = $request->input('picture') ?: null;
        $user['gender'] = $request->input('gender') ?: null;
        $user->save();

        return response()->json([
            'user_id' => $userId,
        ]);
    }

    public function token(Request $request)
    {
        $this->validate($request, [
            'id'          => 'required',
            'accessToken' => 'required',
        ]);

        $userId = $request->input('id');
        $accessToken = $request->input('accessToken');
        $expiresAt = $request->input('expiresAt');

        $user = User::where('user_id', '=', $userId)->first();
        if (empty($user)) {
            $user = new User();
            $user['user_id'] = $userId;
        }

        $user['access_token'] = $accessToken;
        $user['expires_at'] = $expiresAt;
        $user->save();

        return response()->json([
            'user_id' => $userId,
        ]);
    }

    public function login(Request $request)
    {
        session_start();

        $fb = new FB([
            'app_id'     => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = [
            'email',
            'user_gender',
            'pages_messaging',
//            'ads_management',
//            'ads_read',
        ];

        $loginUrl = $helper->getLoginUrl(url('fb/auth/callback', [], true), $permissions);

        $return = $request->input('return');
        if (empty($return)) {
            $_SESSION['return'] = $return;
        }

        return redirect($loginUrl);
    }

    public function callback()
    {
        session_start();

        $fb = new FB([
            'app_id'     => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $accessToken = $helper->getAccessToken(url('fb/auth/callback', [], true));
        if (empty($accessToken)) {
            if ($helper->getError()) {
                return response()->json([
                    'code'    => $helper->getErrorCode(),
                    'message' => $helper->getErrorReason() . $helper->getErrorDescription(),
                ], 401);
            }
        }

        $oAuth2Client = $fb->getOAuth2Client();

        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $tokenMetadata->validateAppId(env('FB_APP_ID'));
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        }

        $response = $fb->get('me?fields=id,email,name,first_name,last_name,picture,gender', $accessToken->getValue());
        $fbUser = $response->getGraphUser();

        $user = User::where('user_id', '=', $fbUser['id'])->first();
        if (empty($user)) {
            $user = new User();
            $user['user_id'] = $fbUser->getId();
        }

        $user['email'] = $fbUser->getEmail();
        $user['name'] = $fbUser->getName();
        $user['first_name'] = $fbUser->getFirstName();
        $user['last_name'] = $fbUser->getLastName();
        $user['picture'] = $fbUser->getPicture() ? $fbUser->getPicture()->getUrl() : null;
        $user['gender'] = $fbUser->getGender();
        $user['access_token'] = $accessToken->getValue();
        $user['expires_at'] = $accessToken->getExpiresAt();
        $user->save();

        if (array_key_exists('return', $_SESSION) and !empty($_SESSION['return'])) {
            $return = $_SESSION['return'];
            unset($_SESSION['return']);

            return redirect($return);
        }

        return response()->json([
            'accessToken' => $accessToken->getValue(),
            'expiresAt'   => $accessToken->getExpiresAt(),
        ]);
    }
}
