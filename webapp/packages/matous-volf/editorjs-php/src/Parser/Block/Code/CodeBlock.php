<?php

declare(strict_types=1);

namespace Setono\EditorJS\Parser\Block\Code;

use Setono\EditorJS\Parser\Block\BlockInterface;
use Setono\EditorJS\Parser\Block\GenericBlock;
use Webmozart\Assert\Assert;

final class CodeBlock extends GenericBlock implements CodeBlockInterface
{
    private string $body;

    public function __construct(string $id, string $type, string $body, array $data)
    {
        parent::__construct($id, $type, $data);

        $this->body = $body;
    }

    public static function createFromBlock(BlockInterface $block): self
    {
        $data = $block->getData();

        self::validate($data);

        return new self(
            $block->getId(),
            $block->getType(),
            $data['data']['code'],
            $block->getData()
        );
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @psalm-assert array{data: array{code: string}} $data
     */
    protected static function validate(array $data): void
    {
        parent::validate($data);

        Assert::keyExists($data['data'], 'code');
        Assert::string($data['data']['code']);
    }
}
