<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait Table
{
    public function getTable(): string
    {
        return Str::snake(class_basename($this));
    }
}
