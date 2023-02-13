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
use Kazetenn\Core\Entity\BaseContent;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page extends BaseContent
{
    const PAGE_TEMPLATE = '@KazetennPages/content/_block_content_display.twig'; // todo: do something about this

    // todo find a way to abstract this one into the Core bundle
    #[ORM\OneToMany(mappedBy: 'baseContent', targetEntity: PageContent::class)]
    #[ORM\OrderBy(["blocOrder" => "asc"])]
    protected Collection $blocks;

    #[ORM\OneToMany(mappedBy: "parent", targetEntity: Page::class)]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: "children")]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "id", nullable: true)]
    private ?Page $parent;

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function setId(UuidV4 $id): void
    {
        $this->id = $id;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    public function getParent(): ?Page
    {
        return $this->parent;
    }

    public function setParent(?Page $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * This return an ordered array of the direct descendant blocks.
     */
    public function getBlocksOrdered(): array{
        $data = $this->blocks;

        $return = [];
        foreach ($data as $datum) {
            if ($datum->getparent() === null) {
                $return[$datum->getBlocOrder()] = $datum;
            }
        }

        return $return;
    }

    /**
     * @param Collection $blocks
     */
    public function setBlocks(Collection $blocks): void
    {
        $this->blocks = $blocks;
    }
}
