<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class BaseContent
{
    const DEFAULT_TEMPLATE = '@KazetennPages/content/_block_content_display.twig'; // todo: do something about this

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidV4 $id;

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
     * @ORM\OneToMany(targetEntity="Kazetenn\Pages\Entity\PageContent", mappedBy="page")
     * @ORM\OrderBy({"blocOrder" = "asc"})
     */
    private Collection $pageContents;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $template;

    public function __construct()
    {
        $this->children     = new ArrayCollection();
        $this->pageContents = new ArrayCollection();

        $this->id = Uuid::v4();

        if (null === $this->template) {
            $this->template = self::DEFAULT_TEMPLATE;
        }
    }
}
