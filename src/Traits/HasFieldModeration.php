<?php

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Gordiichuk\FieldModeration\Models\FieldModeration;
use Gordiichuk\FieldModeration\Enums\ModerationStatus;

trait HasFieldModeration {

    abstract public function moderatedFields(): array;

    public function moderations(): MorphMany
    {
        return $this->morphMany(FieldModeration::class, 'moderatable');
    }

    public function getModeration(string $field): ?FieldModeration
    {
        return $this->moderations()->where('field', $field)->first();
    }

    public function isFieldApproved(string $field): bool
    {
        return $this->getModeration($field)?->isApproved() ?? false;
    }

    public function isFieldPending(string $field): bool
    {
        return $this->getModeration($field)?->isPending() ?? false;
    }

    public function isFieldRejected(string $field): bool
    {
        return $this->getModeration($field)?->isRejected() ?? false;
    }

    public function approveField(string $field, int $moderatedBy = null): ?FieldModeration
    {
        return $this->firstOrCreateModeration($field)->approve($moderatedBy);
    }

    public function rejectField(string $field, string $reason, int $moderatedBy = null): ?FieldModeration
    {
        return $this->firstOrCreateModeration($field)->reject($reason, $moderatedBy);
    }

    public function resetField(string $field): ?FieldModeration
    {
        return $this->firstOrCreateModeration($field)->setPending();
    }

    public function pendingFields(): array
    {
        return $this->moderations()
            ->where('status', ModerationStatus::PENDING)
            ->pluck('field')
            ->toArray();
    }

    public function approvedFields(): array
    {
        return $this->moderations()
            ->where('status', ModerationStatus::APPROVED)
            ->pluck('field')
            ->toArray();
    }

    public function rejectedFields(): array
    {
        return $this->moderations()
            ->where('status', ModerationStatus::REJECTED)
            ->pluck('field')
            ->toArray();
    }

    protected function firstOrCreateModeration(string $field): FieldModeration
    {
        if (!in_array($field, $this->moderatedFields(), true)) {
            throw new \InvalidArgumentException("Field '$field' is not configured for moderation.");
        }

        return $this->moderations()->firstOrCreate(
            ['field' => $field],
            ['status' => ModerationStatus::PENDING]
        );
    }

    protected static function bootHasFieldModeration(): void
    {
        static::created(function ($model) {
            foreach ($model->moderatedFields() as $field) {
                $model->moderations()->firstOrCreate(
                    ['field' => $field],
                    ['status' => ModerationStatus::PENDING]
                );
            }
        });

        static::updating(function ($model) {
            foreach ($model->getDirty() as $field => $value) {
                if (in_array($field, $model->moderatedFields(), true)) {
                    $model->resetField($field);
                }
            }
        });
    }
}