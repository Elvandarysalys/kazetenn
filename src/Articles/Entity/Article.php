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
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    const ARTICLE_TEMPLATE = '@KazetennArticles/content/_block_content_display.twig'; // todo: do something about this

    use TimestampableEntity;

//    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private ?UuidV4 $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Unique()
     */
    private string $slug;

    /**
     * @var ArticleContent[]
     * @ORM\OneToMany(targetEntity="Kazetenn\Articles\Entity\ArticleContent", mappedBy="article")
     * @ORM\OrderBy({"blocOrder" = "asc"})
     */
    private $articleContents;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private ?string $template;

    /**
     * @var Category[]
     * @ORM\ManyToMany(targetEntity="Kazetenn\Articles\Entity\Category", inversedBy="articles")
     * @ORM\JoinTable(name="article_categories")
     */
    private $categories;

    public function __construct()
    {
        $this->articleContents = new ArrayCollection();
        $this->categories      = new ArrayCollection();

        if (null === $this->id) {
            $this->id = Uuid::v4();
        }

        if (null === $this->template) {
            $this->template = self::ARTICLE_TEMPLATE;
        }
    }

    /**
     * @return Collection|ArticleContent[]
     */
    public function getArticleContents(): Collection
    {
        return $this->articleContents;
    }

    /**
     * @return ArticleContent[]
     */
    public function getArticleContentsOrdered(): array
    {
        $data = $this->articleContents;

        $return = [];
        foreach ($data as $datum) {
            if ($datum->getParent() === null) {
                $return[$datum->getBlocOrder()][] = $datum;
            }
        }
        return $return;
    }

    public function addArticleContent(ArticleContent $articleContent): self
    {
        if (!$this->articleContents->contains($articleContent)) {
            $this->articleContents[] = $articleContent;
            $articleContent->setArticle($this);
        }

        return $this;
    }

    public function removeArticleContent(ArticleContent $articleContent): self
    {
        if ($this->articleContents->removeElement($articleContent)) {
            // set the owning side to null (unless already changed)
            if ($articleContent->getArticle() === $this) {
                $articleContent->setArticle(null);
            }
        }

        return $this;
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

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }
}
