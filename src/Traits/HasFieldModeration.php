<?php

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFieldModeration {
    public function moderations(): MorphMany {}
    public function getModeration(string $field): ?FieldModeration {}
}