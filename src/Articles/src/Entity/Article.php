<?php
/*
 * This file is part of the Kazetenn Articles Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Articles\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Kazetenn\Articles\Repository\ArticleRepository;
use Kazetenn\Core\Entity\BaseContent;
use Kazetenn\Pages\Entity\PageContent;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article extends BaseContent
{
    const ARTICLE_TEMPLATE = '@KazetennArticles/content/_block_content_display.twig'; // todo: do something about this

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: "articles")]
    #[ORM\JoinTable(name: "article_categories")]
    protected Collection $categories;

    // todo find a way to abstract this one into the Core bundle
    #[ORM\OneToMany(mappedBy: 'baseContent', targetEntity: PageContent::class)]
    #[ORM\OrderBy(["blocOrder" => "asc"])]
    protected Collection $blocks;

    public function __construct()
    {
        parent::__construct();
        $this->categories      = new ArrayCollection();
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return UuidV4
     */
    public function getId(): UuidV4
    {
        return $this->id;
    }

    /**
     * @param UuidV4 $id
     */
    public function setId(UuidV4 $id): void
    {
        $this->id = $id;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
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
