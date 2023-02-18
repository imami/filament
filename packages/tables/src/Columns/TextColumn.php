<?php

namespace Filament\Tables\Columns;

use BackedEnum;
use Closure;
use Filament\Support\Contracts\HasLabel as LabelInterface;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Arrayable;
use stdClass;

class TextColumn extends Column
{
    use Concerns\CanBeCopied;
    use Concerns\CanFormatState;
    use Concerns\HasColor;
    use Concerns\HasDescription;
    use Concerns\HasFontFamily;
    use Concerns\HasIcon;
    use Concerns\HasSize;
    use Concerns\HasWeight;

    /**
     * @var view-string
     */
    protected string $view = 'filament-tables::columns.text-column';

    protected bool | Closure $canWrap = false;

    protected ?string $enum = null;

    protected bool | Closure $isBadge = false;

    protected bool | Closure $isBulleted = false;

    protected bool | Closure $isListWithLineBreaks = false;

    protected int | Closure | null $listLimit = null;

    public function badge(bool | Closure $condition = true): static
    {
        $this->isBadge = $condition;

        return $this;
    }

    public function bulleted(bool | Closure $condition = true): static
    {
        $this->isBulleted = $condition;

        return $this;
    }

    public function listWithLineBreaks(bool | Closure $condition = true): static
    {
        $this->isListWithLineBreaks = $condition;

        return $this;
    }

    public function limitList(int | Closure | null $limit = 3): static
    {
        $this->listLimit = $limit;

        return $this;
    }

    public function enum(?string $enum): static
    {
        $this->enum = $enum;

        return $this;
    }

    public function rowIndex(bool $isFromZero = false): static
    {
        $this->getStateUsing(static function (HasTable $livewire, stdClass $rowLoop) use ($isFromZero): string {
            $rowIndex = $rowLoop->{$isFromZero ? 'index' : 'iteration'};

            return (string) ($rowIndex + ($livewire->getTableRecordsPerPage() * ($livewire->getTablePage() - 1)));
        });

        return $this;
    }

    public function wrap(bool | Closure $condition = true): static
    {
        $this->canWrap = $condition;

        return $this;
    }

    public function canWrap(): bool
    {
        return (bool) $this->evaluate($this->canWrap);
    }

    public function isBadge(): bool
    {
        return (bool) $this->evaluate($this->isBadge);
    }

    public function isBulleted(): bool
    {
        return (bool) $this->evaluate($this->isBulleted);
    }

    public function isListWithLineBreaks(): bool
    {
        return $this->evaluate($this->isListWithLineBreaks) || $this->isBulleted();
    }

    public function getListLimit(): ?int
    {
        return $this->evaluate($this->listLimit);
    }
}
