<?php

declare(strict_types=1);

namespace Setono\EditorJS\Parser\Block\Table;

use Setono\EditorJS\Parser\Block\BlockInterface;

interface TableBlockInterface extends BlockInterface
{
    /**
     * @return array<array-key, array>
     */
    public function getCells(): array;
    public function isWithHeadings(): bool;
}
