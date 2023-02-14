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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kazetenn\Articles\Repository\CategoryRepository;
use Kazetenn\Articles\Entity\Article;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    use TimestampableEntity;
    use BlameableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    protected UuidV4 $id;

    #[ORM\OneToMany(mappedBy: "parent", targetEntity: Category::class)]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "children")]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "id", nullable: true)]
    private ?Category $parent;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $slug;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: "categories")]
    #[ORM\JoinTable(name: "article_categories")]
    private Collection $articles;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->articles = new ArrayCollection();

        $this->id = Uuid::v4();
    }

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

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function setParent(?Category $parent): void
    {
        $this->parent = $parent;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function setArticles(Collection $articles): void
    {
        $this->articles = $articles;
    }
}
