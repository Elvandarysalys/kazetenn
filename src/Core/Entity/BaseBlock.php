<?php
/*
 * This file is part of the Kazetenn Pages Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\MappedSuperclass]
abstract class BaseBlock implements BaseBlockInterface
{
    const ROW_TEMPLATE     = '@Core/content/_block_content_display.twig';
    const HORIZONTAL_ALIGN = 'horizontal';
    const VERTICAL_ALIGN   = 'vertical';

    use TimestampableEntity;
    use BlameableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    protected UuidV4 $id;

    #[ORM\ManyToOne(targetEntity: BaseContent::class)]
    #[ORM\JoinColumn(name: "content_id", referencedColumnName: "id")]
    protected ?BaseContent $baseContent;

    #[ORM\ManyToOne(targetEntity: BaseBlock::class)]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    protected ?BaseBlockInterface $parent;

    // OneToMany association does not work with MappedSuperclass. todo: check how to handle this, can it be handled in a service or not ?
    //    #[ORM\OneToMany(mappedBy: "parent", targetEntity: BaseBlock::class)]
    //    #[ORM\JoinColumn(name: "children_id", referencedColumnName: "id")]
    protected Collection $children;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $content = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    protected string $template = self::ROW_TEMPLATE;

    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $blocOrder;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    protected string $align = self::VERTICAL_ALIGN;

    public function __construct()
    {
        $this->id       = Uuid::v4();
        $this->template = self::ROW_TEMPLATE;

        $this->children = new ArrayCollection();
    }

    public function addChildren(BaseBlock $baseBlock): self
    {
        if (!$this->children->contains($baseBlock)) {
            $this->children[] = $baseBlock;
            $baseBlock->setParent($this);
        }

        return $this;
    }

    public function removeChildren(BaseBlock $baseBlock): self
    {
        if ($this->children->removeElement($baseBlock)) {
            if (null !== $baseBlock->getBaseContent() && $baseBlock->getBaseContent()->getId() === $this->getId()) {
                $baseBlock->setParent(null);
            }
        }

        return $this;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function setId(UuidV4 $id): void
    {
        $this->id = $id;
    }

    public function getBaseContent(): ?BaseContent
    {
        return $this->baseContent;
    }

    public function setBaseContent(?BaseContent $baseContent): void
    {
        $this->baseContent = $baseContent;
    }

    public function getParent(): ?BaseBlockInterface
    {
        return $this->parent;
    }

    public function setParent(?BaseBlockInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getBlocOrder(): int
    {
        return $this->blocOrder;
    }

    public function setBlocOrder(int $blocOrder): void
    {
        $this->blocOrder = $blocOrder;
    }

    public function getAlign(): string
    {
        return $this->align;
    }

    public function setAlign(string $align): void
    {
        $this->align = $align;
    }
}
