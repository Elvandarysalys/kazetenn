<?php

namespace Kazetenn\Core\Model;

use Kazetenn\Core\Entity\BaseBlockInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('kazetenn.block_type_tag')]
interface BlockInterface
{
    public function getBlockName(): string;

    public function getModel(): array;

    public function getBlockContent(BaseBlockInterface $content): string;
}
