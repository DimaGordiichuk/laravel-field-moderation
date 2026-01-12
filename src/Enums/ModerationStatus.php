<?php

namespace FieldModerations\Enums;

enum ModerationStatus: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}