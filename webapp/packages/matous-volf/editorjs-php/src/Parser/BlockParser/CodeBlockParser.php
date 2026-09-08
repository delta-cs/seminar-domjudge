<?php

declare(strict_types=1);

namespace Setono\EditorJS\Parser\BlockParser;

use Setono\EditorJS\Parser\Block\Code\CodeBlock;

final class CodeBlockParser extends GenericBlockParser
{
    protected function getType(): string
    {
        return 'code';
    }

    protected function getBlockClass(): string
    {
        return CodeBlock::class;
    }
}
