<?php

namespace Gordiichuk\FieldModeration\Models;

use Illuminate\Database\Eloquent\Model;
use Gordiichuk\FieldModeration\Enums\ModerationStatus;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FieldModeration extends Model {

    protected $table = 'model_field_moderations';

    protected $guarded = ['id'];
    protected $casts = [
        'status' => ModerationStatus::class,
        'moderated_at' => 'datetime',
    ];

    public function moderatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function setPending(): self
    {
        $this->status = ModerationStatus::PENDING;
        $this->rejection_reason = null;
        $this->moderated_at = null;
        $this->save();

        return $this;
    }

    public function approve(int $moderatedBy = null): self
    {
        $this->status = ModerationStatus::APPROVED;
        $this->moderated_at = now();

        if ($moderatedBy) {
            $this->moderated_by = $moderatedBy;
        }

        $this->rejection_reason = null;

        $this->save();

        return $this;
    }

    public function reject(string $reason, int $moderatedBy = null): self
    {
        $this->status = ModerationStatus::REJECTED;
        $this->rejection_reason = $reason;
        $this->moderated_at = now();

        if ($moderatedBy) {
            $this->moderated_by = $moderatedBy;
        }

        $this->save();

        return $this;
    }

    public function isPending(): bool
    {
        return $this->status === ModerationStatus::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === ModerationStatus::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === ModerationStatus::REJECTED;
    }
}