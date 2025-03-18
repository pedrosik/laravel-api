<?php

declare(strict_types=1);

namespace App\ValueObjects\Concerns;

trait WithLabels
{
    abstract public function label(): string;

    public static function withLabels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $enum) => [$enum->value => $enum->label()])
            ->toArray();
    }

    public static function fromLabel(string $label): self
    {
        $map = array_flip(self::withLabels());

        if (!isset($map[$label])) {
            throw new \InvalidArgumentException("Incorrect label {$label}");
        }

        return self::from($map[$label]);
    }
}
