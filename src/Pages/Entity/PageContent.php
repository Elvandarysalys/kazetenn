<?php
/*
 * This file is part of the Kazetenn Pages Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Pages\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Kazetenn\Core\Entity\BaseBlock;
use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Entity\BaseContent;
use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Pages\Repository\PageContentRepository;

#[ORM\Entity(repositoryClass: PageContentRepository::class)]
class PageContent extends BaseBlock
{
    const ROW_TEMPLATE     = '@KazetennPages/content/_block_content_display.twig';

    // todo find a way to abstract this one into the Core bundle
    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: 'blocks')]
    #[ORM\JoinColumn(name: "content_id", referencedColumnName: "id")]
    protected ?BaseContent $baseContent;

    // todo find a way to abstract this one into the Core bundle
    #[ORM\ManyToOne(targetEntity: PageContent::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    protected ?BaseBlockInterface $parent;

    // todo find a way to abstract this one into the Core bundle
    #[ORM\OneToMany(mappedBy: "parent", targetEntity: PageContent::class)]
    #[ORM\JoinColumn(name: "children_id", referencedColumnName: "id")]
    protected Collection $children;

    /**
     * @return BaseContent|null
     */
    public function getBaseContent(): ?BaseContent
    {
        return $this->baseContent;
    }

    /**
     * @param BaseContent|null $baseContent
     */
    public function setBaseContent(?BaseContentInterface $baseContent): void
    {
        $this->baseContent = $baseContent;
    }

    /**
     * @return BaseBlockInterface|null
     */
    public function getParent(): ?BaseBlockInterface
    {
        return $this->parent;
    }

    /**
     * @param BaseBlockInterface|null $parent
     */
    public function setParent(?BaseBlockInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     */
    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }
}
