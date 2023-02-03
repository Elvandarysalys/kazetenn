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
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kazetenn\Articles\Repository\ArticleRepository;
use Kazetenn\Core\Entity\BaseContent;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article extends BaseContent
{
    const ARTICLE_TEMPLATE = '@KazetennArticles/content/_block_content_display.twig'; // todo: do something about this

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    protected UuidV4 $id;

    /**
     * @var Category[]
     * @ORM\ManyToMany(targetEntity="Kazetenn\Articles\Entity\Category", inversedBy="articles")
     * @ORM\JoinTable(name="article_categories")
     */
    protected $categories;

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
}
