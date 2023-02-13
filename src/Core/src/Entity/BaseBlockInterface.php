<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Entity;

use Kazetenn\Core\Entity\BaseBlock;
use Kazetenn\Core\Entity\BaseContent;
use Kazetenn\Core\Entity\BaseContentInterface;
use Symfony\Component\Uid\UuidV4;

interface BaseBlockInterface
{
    const HORIZONTAL_ALIGN = 'horizontal';
    const VERTICAL_ALIGN   = 'vertical';

    public function addChildren(BaseBlock $baseBlock): self;

    public function removeChildren(BaseBlock $baseBlock): self;

    public function getId(): UuidV4;

    public function setId(UuidV4 $id): void;

    public function getBaseContent(): ?BaseContent;

    public function setBaseContent(?BaseContentInterface $baseContent): void;

    public function getParent(): ?BaseBlockInterface;

    public function setParent(?BaseBlockInterface $parent): void;

    public function getContent(): ?string;

    public function setContent(?string $content): void;

    public function getTemplate(): string;

    public function setTemplate(string $template): void;

    public function getBlocOrder(): int;

    public function setBlocOrder(int $blocOrder): void;

    public function getAlign(): string;

    public function setAlign(string $align): void;
}
