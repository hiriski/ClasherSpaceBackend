<?php

namespace App\Services;

use App\Models\Feedback;

class FeedbackService
{

  /**
   * create item
   */
  public function create($params)
  {
    $data = Feedback::create($params);
    return $data->fresh();
  }
}
