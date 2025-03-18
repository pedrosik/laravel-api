<?php

declare(strict_types=1);

namespace App\ValueObjects\Concerns;

trait Arrayable
{
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }
}
