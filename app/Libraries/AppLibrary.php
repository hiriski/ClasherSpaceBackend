<?php

namespace App\Libraries;

use Carbon\Carbon;
use App\Models\User;
use InvalidArgumentException;

class AppLibrary
{
  public static function formatDate($date, $pattern = null): string
  {
    if (!$pattern) {
      $pattern = env('DATE_FORMAT');
    }
    return Carbon::parse($date)->format($pattern);
  }

  public static function formatTime($time, $pattern = null): string
  {
    if (!$pattern) {
      $pattern = env('TIME_FORMAT');
    }
    return Carbon::parse($time)->format($pattern);
  }

  public static function formatDateTime($dateTime, $pattern = null): string
  {
    if (!$pattern) {
      $pattern = env('TIME_FORMAT') . ', ' . env('DATE_FORMAT');
    }
    return Carbon::parse($dateTime)->format($pattern);
  }

  public static function increaseDate($dateTime, $days, $pattern = null): string
  {
    if (!$pattern) {
      $pattern = env('DATE_FORMAT');
    }
    return Carbon::parse($dateTime)->addDays($days)->format($pattern);
  }

  public static function pluck($array, $value, $key = null, $type = 'object'): array
  {
    $returnArray = [];
    if ($array) {
      foreach ($array as $item) {
        if ($key != null) {
          if ($type == 'array') {
            $returnArray[$item[$key]] = strtolower($value) == 'obj' ? $item : $item[$value];
          } else {
            $returnArray[$item[$key]] = strtolower($value) == 'obj' ? $item : $item->$value;
          }
        } elseif ($value == 'obj') {
          $returnArray[] = $item;
        } elseif ($type == 'array') {
          $returnArray[] = $item[$value];
        } else {
          $returnArray[] = $item->$value;
        }
      }
    }
    return $returnArray;
  }

  public static function generateUsername($name)
  {
    if ($name) {
      $username = strtolower(str_replace(' ', '', $name)) . rand(1, 99999);
      if (User::where(['username' => $username])->first()) {
        self::generateUsername($name);
      }
      return $username;
    }
  }

  public static function deleteDir($dirPath): void
  {
    if (!is_dir($dirPath)) {
      throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
      $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
      if (is_dir($file)) {
        self::deleteDir($file);
      } else {
        unlink($file);
      }
    }
    rmdir($dirPath);
  }
}
