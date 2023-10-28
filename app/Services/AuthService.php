<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\ResetPasswordToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use \Laravel\Sanctum\PersonalAccessToken;

class AuthService
{

  /**
   * Register with email and password
   */
  public function registerWithEmailAndPassword($data)
  {
    $newUser = $data;
    $newUser['password'] = Hash::make($data['password']);

    $newUser['avatar_text_color'] = '#3391ff';

    if (isset($data['username']) && $data['username']) {
      $newUser['username']  = $data['username'];
    } else {
      $newUser['username']  = Str::slug($data['name'], '_') . time();
    }

    return User::create($newUser);
  }

  /**
   * Check availability username when register in mobile app
   */
  public function checkAvailabilityUsername($username)
  {
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
  }


  /**
   * Login
   * 
   * @param @param App\Http\Request\LoginRequest $request;
   * @return \Illuminate\Http\Response
   */
  public function loginWithEmailAndPassword($data)
  {
    if (Auth::attempt($data)) {
      return User::where('email', $data['email'])->first();
    }
    return;
  }


  /**
   * Send reset password link.
   */
  public function sendResetPasswordLink(array $data)
  {
    $plainTextToken   = Str::random(36);
    $appId            = isset($data['app_id']) ? $data['app_id'] : 'community-app-v1.0';
    $email            = $data['email'];
    $resetLink        = config('app.app_frontend_url') . "/reset-password?app_id=$appId&token=$plainTextToken&email=$email";

    $passwordReset = ResetPasswordToken::where('email', $email)->first();

    if ($passwordReset) {
      // Update existing password reset.
      $passwordReset->token = Hash::make($plainTextToken);
      $passwordReset->save();
    } else {
      $passwordReset = ResetPasswordToken::create([
        'email'       => $email,
        'token'       => Hash::make($plainTextToken),
        'created_at'  => Carbon::now(),
        'app_id'      => $appId,
      ]);
    }

    // Send $resetLink to email
    Mail::to($email)->send(new \App\Mail\ResetPasswordInstruction([
      'link'  => $resetLink,
      'email' => $email,
    ]));

    return $passwordReset;
  }


  public function verifyTokenPasswordReset($data)
  {
    $user           = User::where('email', $data['email'])->first();
    $passwordReset  = ResetPasswordToken::where('email', $data['email'])->first();
    if ($user && Hash::check($data['token'], $passwordReset->token)) {
      return $user;
    } else {
      return false;
    }
  }

  /**
   * Reset password
   */
  public function resetPassword($data)
  {
    $passwordReset = ResetPasswordToken::where('email', $data['email'])->first();
    if ($passwordReset !== null && Hash::check($data['token'], $passwordReset->token)) {
      $user = User::where('email', $data['email'])->first();
      $user->password = Hash::make($data['password']);
      $user->save();

      // Then delete reset password row.
      $passwordReset->delete();

      return $user;
    } else {
      return;
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
