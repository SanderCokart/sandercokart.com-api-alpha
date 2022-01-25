<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Status;

class StatusController extends Controller
{
    public function post(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Status::find([
            Status::UNLISTED,
            Status::PUBLISHED,
            Status::ARCHIVED,
        ]);
    }
}
