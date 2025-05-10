<?php


namespace App\Services\ClashOfClans;

use Exception;
use Illuminate\Support\Facades\Http;

class ClashOfClansApiService
{
  public function get(string $path, array | null $queryParams = null)
  {
    try {
      $http = Http::withHeaders([
        'Authorization' => "Bearer " . config('app.coc_api_key'),
      ]);

      if ($queryParams) {
        $http->withQueryParameters($queryParams);
      }

      $pendingRequest = $http->get(config('app.coc_api_base_url') . $path);

      if ($pendingRequest->status() === 200) {
        return $pendingRequest->json();
      }

      throw new Exception($pendingRequest->body());
    } catch (Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }


  public function post(string $path, array $body = null): array
  {
    try {
      $http = Http::withHeaders([
        'Authorization' => "Bearer " . config('app.coc_api_key'),
      ]);

      if ($body) {
        $http->withQueryParameters($body);
      }

      $pendingRequest = $http->post(config('app.coc_api_base_url') . $path);

      if ($pendingRequest->status() === 200) {
        return $pendingRequest->json();
      }

      throw new Exception($pendingRequest->body());
    } catch (Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }
}
