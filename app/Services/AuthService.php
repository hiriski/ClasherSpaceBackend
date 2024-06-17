<?php

namespace App\Services;

use App\Events\EventSendResetPasswordLink;
use App\Http\Requests\Auth\GoogleSignInMobileRequest;
use App\Libraries\AppLibrary;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\ResetPasswordToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use \Laravel\Sanctum\PersonalAccessToken;
use App\Http\Requests\Auth\LoginWithEmailAndPasswordRequest;
use App\Http\Requests\Auth\RegisterWithEmailAndPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\AuthProvider;

class AuthService
{

  /**
   * @throws Exception
   */
  public function registerWithEmailAndPassword(RegisterWithEmailAndPasswordRequest $request)
  {
    try {
      $data = $request->validated();
      $data['password'] = Hash::make($request->password);
      $data['avatarTextColor'] = '#3391ff';

      if ($request->username) {
        $data['username'] = $request->username;
      } else {
        $data['username'] = AppLibrary::generateUsername($request->name);
      }
      return User::create($data);
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage(), 422);
    }
  }

  /**
   * @throws Exception
   */
  public function loginWithEmailAndPassword(LoginWithEmailAndPasswordRequest $request)
  {
    try {
      if (Auth::attempt($request->validated())) {
        return User::where('email', $request->email)->first();
      } else {
        throw new Exception(trans('auth.invalid_credentials'));
      }
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage(), 422);
    }
  }

  /**
   * @throws Exception 
   */
  public function googleSignInMobile(GoogleSignInMobileRequest $request)
  {
    try {
      $user = User::where('email', $request->providerEmail)->first();
      $authProvider  = AuthProvider::updateOrCreate(
        [
          'providerAccountId'   => $request->providerAccountId,
          'providerName'        => $request->providerName,
        ],
        [
          'providerAccountName' => $request->providerAccountName,
          'providerPhotoUrl'    => $request->providerPhotoUrl,
        ]
      );
      if (!$user) {
        $userName = AppLibrary::generateUsername($request->providerAccountName);
        $user = User::create([
          'name'              => $request->providerAccountName,
          'username'          => $userName,
          'email'             => $request->providerEmail,
          'avatarTextColor'   => '#3391ff',
        ]);
        $authProvider->fill(['userId' => $user->id])->save();
      }
      return $user->fresh();
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage(), 422);
    }
  }


  /**
   * @throws Exception
   */
  public function checkAvailabilityUsername($username)
  {
    try {
      $user = User::where('username', $username)->first();
      if ($user) {
        return [
          'availability'     => false
        ];
      } else {
        return [
          'availability'     => true
        ];
      }
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage(), 422);
    }
  }

  /**
   * @throws Exception
   */
  public function sendResetPasswordLink(string $email, string $appId)
  {
    try {
      $userIsExists = User::where('email', $email)->exists();
      if ($userIsExists) {
        $plainTextToken   = Str::random(36);
        $resetLink        = config('app.app_frontend_url') . "/reset-password?appId=$appId&token=$plainTextToken&email=$email";

        $passwordReset = ResetPasswordToken::where('email', $email)->first();

        if ($passwordReset) {
          // Update existing password reset. 
          $passwordReset->token = Hash::make($plainTextToken);
          $passwordReset->save();
        } else {
          $passwordReset = ResetPasswordToken::create([
            'email'       => $email,
            'token'       => Hash::make($plainTextToken),
            'createdAt'   => Carbon::now(),
            'appId'       => $appId,
          ]);
        }

        if ($passwordReset) {
          // Send $resetLink to email
          // Mail::to($email)->send(new \App\Mail\ResetPasswordInstruction([
          //   'link'  => $resetLink,
          //   'email' => $email,
          // ]));
          EventSendResetPasswordLink::dispatch(['email' => $email, 'link' => $resetLink]);
          return trans('auth.reset_password_link_has_been_send');
        } else {
          throw new Exception(trans('auth.token_created_fail'));
        }
      } else {
        throw new Exception(trans('auth.email_does_not_exist'));
      }
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage());
    }
  }


  /**
   * @throws Exception
   */
  public function verifyTokenPasswordReset(string $email, string $token)
  {
    try {
      $user = User::where('email', $email)->first();
      $passwordReset = ResetPasswordToken::where('email', $email)->first();
      if ($user && Hash::check($token, $passwordReset->token)) {
        return $user;
      } else {
        throw new Exception(trans('auth.invalid_token'));
      }
    } catch (Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * @throws Exception
   */
  public function resetPassword(ResetPasswordRequest $request)
  {
    try {
      $passwordReset = ResetPasswordToken::where('email', $request->email)->first();
      if ($passwordReset && Hash::check($request->token, $passwordReset->token)) {

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Then delete reset password row.
        $passwordReset->delete();

        return $user->fresh();
      } else {
        throw new Exception(trans('auth.reset_password_failed'));
      }
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * Delete user current access token.
   * 
   * @return boolean
   */
  public function deleteCurrentAccessToken($currentAccessToken)
  {
    if ($currentAccessToken) {
      // $user = User::findOrFail($userId);
      // dd($user->currentAccessToken()->delete()); // I can't delete this token ? idk!
      return PersonalAccessToken::findToken($currentAccessToken)->delete();
    } else {
      return  false;
    }
  }
}
