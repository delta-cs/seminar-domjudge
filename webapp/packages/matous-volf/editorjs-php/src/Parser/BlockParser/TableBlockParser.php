<?php

declare(strict_types=1);

namespace Setono\EditorJS\Parser\BlockParser;

use Setono\EditorJS\Parser\Block\Table\TableBlock;

final class TableBlockParser extends GenericBlockParser
{
    protected function getType(): string
    {
        return 'table';
    }

    protected function getBlockClass(): string
    {
        return TableBlock::class;
    }
}
