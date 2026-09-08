<?php

declare(strict_types=1);

// the 'Block' in 'ListBlock' is only because reserved keywords cannot be part of a namespace before PHP8

namespace Setono\EditorJS\Parser\Block\Table;

use Setono\EditorJS\Parser\Block\BlockInterface;
use Setono\EditorJS\Parser\Block\GenericBlock;
use Webmozart\Assert\Assert;

final class TableBlock extends GenericBlock implements TableBlockInterface
{
    private bool $withHeadings;

    /** @var array<array-key, array<array-key, string>> */
    private array $cells;

    /**
     * @param array<array-key, array<array-key, string>> $cells
     */
    public function __construct(string $id, string $type, bool $withHeadings, array $cells, array $data)
    {
        parent::__construct($id, $type, $data);

        $this->withHeadings = $withHeadings;
        $this->cells = $cells;
    }

    public static function createFromBlock(BlockInterface $block): self
    {
        $data = $block->getData();

        self::validate($data);

        return new self(
            $block->getId(),
            $block->getType(),
            $data['data']['withHeadings'],
            $data['data']['content'],
            $block->getData()
        );
    }

    public function isWithHeadings(): bool
    {
        return $this->withHeadings;
    }

    public function getCells(): array
    {
        return $this->cells;
    }

    /**
     * @psalm-assert array{data: array{withHeadings: bool, content: array<array-key, array<array-key, string>>}} $data
     */
    protected static function validate(array $data): void
    {
        parent::validate($data);

        Assert::keyExists($data['data'], 'withHeadings');
        Assert::boolean($data['data']['withHeadings']);

        Assert::keyExists($data['data'], 'content');
        Assert::isArray($data['data']['content']);
        Assert::allIsArray($data['data']['content']);

        foreach ($data['data']['content'] as $row) {
            Assert::allString($row);
        }
    }
}
