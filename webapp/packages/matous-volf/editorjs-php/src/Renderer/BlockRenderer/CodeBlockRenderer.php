<?php

declare(strict_types=1);

namespace Setono\EditorJS\Renderer\BlockRenderer;

use Setono\EditorJS\Parser\Block\BlockInterface;
use Setono\EditorJS\Parser\Block\Code\CodeBlockInterface;
use Setono\EditorJS\Renderer\HtmlBuilder;

final class CodeBlockRenderer extends GenericBlockRenderer
{
    public function render(BlockInterface $block): string
    {
        \assert($block instanceof CodeBlockInterface);

        return (string) HtmlBuilder::create('pre')
            ->withClasses($this->options['classes'])
            ->append($block->getBody())
        ;
    }

    protected function getInterface(): string
    {
        return CodeBlockInterface::class;
    }
}
