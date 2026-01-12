<?php

use Illuminate\Database\Eloquent\Model;
use ModerationStatus;

class FieldModeration extends Model {
    protected $casts = [
        'status' => ModerationStatus::class,
        'moderated_at' => 'datetime',
    ];
}