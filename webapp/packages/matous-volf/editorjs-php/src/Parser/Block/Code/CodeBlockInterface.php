<?php

declare(strict_types=1);

namespace Setono\EditorJS\Parser\Block\Code;

use Setono\EditorJS\Parser\Block\BlockInterface;

interface CodeBlockInterface extends BlockInterface
{
    public function getBody(): string;
}
