<?php

namespace Gordiichuk\FieldModeration\Enums;

enum ModerationStatus: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}