<?php

declare(strict_types=1);

namespace Setono\EditorJS\Renderer\BlockRenderer;

use Setono\EditorJS\Parser\Block\BlockInterface;
use Setono\EditorJS\Parser\Block\Table\TableBlockInterface;
use Setono\EditorJS\Renderer\HtmlBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TableBlockRenderer extends GenericBlockRenderer
{
    public function render(BlockInterface $block): string
    {
        \assert($block instanceof TableBlockInterface);

        $table = HtmlBuilder::create('table')
            ->withClass('table');

        foreach ($block->getCells() as $i => $row) {
            $isHeading = $i == 0 && $block->isWithHeadings();

            $table = $table->append(
                HtmlBuilder::create('tr')
                    ->append(...array_map(
                        fn(string $col) => HtmlBuilder::create($isHeading ? 'th' : 'td')->append($col),
                        $row
                    ))
            );
        }

        return (string)$table;
    }

    /**
     * @psalm-assert array{itemClasses: array<array-key, string>} $this->options
     */
    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefault('itemClasses', [])
            ->setAllowedTypes('itemClasses', 'array');
    }

    protected function getInterface(): string
    {
        return TableBlockInterface::class;
    }
}
