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
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kazetenn\Pages\Repository\PageContentRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=PageContentRepository::class)
 */
class PageContent
{
    const ROW_TEMPLATE     = '@KazetennPages/content/_block_content_display.twig';
    const HORIZONTAL_ALIGN = 'horizontal';
    const VERTICAL_ALIGN   = 'vertical';

    use TimestampableEntity;
//    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var Page|null
     * @ORM\ManyToOne(targetEntity="Kazetenn\Pages\Entity\Page", inversedBy="pageContents")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private ?Page $page;

    /**
     * @var PageContent|null
     * @ORM\ManyToOne(targetEntity="Kazetenn\Pages\Entity\PageContent", inversedBy="childrens")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private ?PageContent $parent;

    /**
     * @var PageContent[]|null
     * @ORM\OneToMany(targetEntity="Kazetenn\Pages\Entity\PageContent", mappedBy="parent")
     * @ORM\JoinColumn(name="children_id", referencedColumnName="id")
     */
    private $childrens;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content = null;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $template = self::ROW_TEMPLATE;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $blocOrder;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $align = self::VERTICAL_ALIGN;

    public function __construct()
    {
        if (null === $this->id) {
            $this->id = Uuid::v4();
        }
        if (null === $this->template) {
            $this->template = self::ROW_TEMPLATE;
        }

        $this->childrens = new ArrayCollection();
    }

    public function addChildren(PageContent $pageContent): self
    {
        if (!$this->childrens->contains($pageContent)) {
            $this->childrens[] = $pageContent;
            $pageContent->setParent($this);
        }

        return $this;
    }

    public function removeChildren(PageContent $pageContent): self
    {
        if ($this->childrens->removeElement($pageContent)) {
            if ($pageContent->getPage() === $this) {
                $pageContent->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return UuidV4
     */
    public function getId()
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
     * @return Page|null
     */
    public function getPage(): ?Page
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return int
     */
    public function getBlocOrder(): int
    {
        return $this->blocOrder;
    }

    /**
     * @param int $blocOrder
     */
    public function setBlocOrder(int $blocOrder): void
    {
        $this->blocOrder = $blocOrder;
    }

    /**
     * @return PageContent|null
     */
    public function getParent(): ?PageContent
    {
        return $this->parent;
    }

    /**
     * @param PageContent|null $parent
     */
    public function setParent(?PageContent $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getAlign(): string
    {
        return $this->align;
    }

    /**
     * @param string $align
     */
    public function setAlign(string $align): void
    {
        $this->align = $align;
    }

    /**
     * @return PageContent[]|null
     */
    public function getChildrens()
    {
        return $this->childrens;
    }

    /**
     * @return PageContent[]
     */
    public function getChildrensOrdered(): array
    {
        /** @var PageContent[] $data */
        $data = $this->childrens;

        $return = [];
        foreach ($data as $datum) {
            $return[$datum->getBlocOrder()] = $datum;
        }
        return $return;
    }

    /**
     * @param PageContent|null $childrens
     */
    public function setChildrens(?PageContent $childrens): void
    {
        $this->childrens = $childrens;
    }
}
