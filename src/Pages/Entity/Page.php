<?php
/*
 * This file is part of the Kazetenn Pages Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Pages\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 */
class Page
{
    const PAGE_TEMPLATE = '@KazetennPages/content/_block_content_display.twig'; // todo: do something about this

    use TimestampableEntity;
//    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private ?UuidV4 $id;

    /**
     * @var Page[]
     * @ORM\OneToMany(targetEntity="Kazetenn\Pages\Entity\Page", mappedBy="parent")
     */
    private $children;

    /**
     * @var Page|null
     * @ORM\ManyToOne(targetEntity="Kazetenn\Pages\Entity\Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private Page $parent;

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
     * @var PageContent[]
     * @ORM\OneToMany(targetEntity="Kazetenn\Pages\Entity\PageContent", mappedBy="page")
     * @ORM\OrderBy({"blocOrder" = "asc"})
     */
    private $pageContents;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private ?string $template;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->pageContents = new ArrayCollection();

        if (null === $this->id) {
            $this->id = Uuid::v4();
        }

        if (null === $this->template){
            $this->template = self::PAGE_TEMPLATE;
        }
    }

    /**
     * @return Collection|PageContent[]
     */
    public function getPageContents(): Collection
    {
        return $this->pageContents;
    }

    /**
     * @return PageContent[]
     */
    public function getPageContentsOrdered(): array
    {
        $data = $this->pageContents;

        $return = [];
        foreach ($data as $datum){
            if($datum->getParent() === null) {
                $return[$datum->getBlocOrder()][] = $datum;
            }
        }
        return $return;
    }

    public function addPageContent(PageContent $pageContent): self
    {
        if (!$this->pageContents->contains($pageContent)) {
            $this->pageContents[] = $pageContent;
            $pageContent->setPage($this);
        }

        return $this;
    }

    public function removePageContent(PageContent $pageContent): self
    {
        if ($this->pageContents->removeElement($pageContent)) {
            // set the owning side to null (unless already changed)
            if ($pageContent->getPage() === $this) {
                $pageContent->setPage(null);
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
     * @return ArrayCollection|Page[]
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
     * @return Page|null
     */
    public function getParent(): ?Page
    {
        return $this->parent;
    }

    /**
     * @param Page|null $parent
     */
    public function setParent(?Page $parent): void
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
