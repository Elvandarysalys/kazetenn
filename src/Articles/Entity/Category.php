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
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kazetenn\Articles\Repository\CategoryRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
      use TimestampableEntity;
//    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private ?UuidV4 $id;

    /**
     * @var Category[]
     * @ORM\OneToMany(targetEntity="Kazetenn\Articles\Entity\Category", mappedBy="parent")
     */
    private $children;

    /**
     * @var Category|null
     * @ORM\ManyToOne(targetEntity="Kazetenn\Articles\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private Category $parent;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $slug;

    /**
     * @var Article[]
     * @ORM\ManyToMany(targetEntity="Kazetenn\Articles\Entity\Article", mappedBy="categories")
     * @ORM\JoinTable(name="article_categories")
     */
    private $articles;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->articles = new ArrayCollection();

        if (null === $this->id) {
            $this->id = Uuid::v4();
        }
    }

    /**
     * @return UuidV4|null
     */
    public function getId(): ?UuidV4
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return ArrayCollection|Category[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

    /**
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param Category|null $parent
     */
    public function setParent(?Category $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
