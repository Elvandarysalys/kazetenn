<?php

namespace Kazetenn\Admin\Model;

class AdminMenu
{
    public const DEFAULT_TRANSLATION_DOMAIN = 'kazetenn_admin';

    public const PAGES_ENTRIES_NAME = 'pages';
    public const PAGE_NAME          = 'name';
    public const PAGE_FUNCTION      = 'function';

    public const MENU_ENTRIES_NAME       = 'menu_entries';
    public const MENU_NAME               = 'name';
    public const MENU_ORDER              = 'order';
    public const MENU_TARGET             = 'target';
    public const MENU_DISPLAY_NAME       = 'display_name';
    public const MENU_TRANSLATION_DOMAIN = 'translation_domain';
    public const MENU_AUTHORIZED_ROLES   = 'authorized_roles';
    public const MENU_TYPE               = 'type';
    public const MENU_CHILDREN           = 'children';
    public const MENU_ORIENTATION        = 'orientation';

    public const ORIENTATION_HORIZONTAL = 'horizontal';
    public const ORIENTATION_VERTICAL   = 'vertical';

    public const LINK_TYPE   = 'link';
    public const PAGE_TYPE   = 'page';
    public const ROUTE_TYPE  = 'route';
    public const HEADER_TYPE = 'header';

    public const MAIN_MENU_TYPES = [self::LINK_TYPE, self::PAGE_TYPE, self::ROUTE_TYPE, self::HEADER_TYPE];
    public const SUB_MENU_TYPES  = [self::LINK_TYPE, self::PAGE_TYPE, self::ROUTE_TYPE];

    public const ANONYMOUS_MENU = 'ANONYMOUS';

    private string $name;
    private string $destination;
    private string $displayName;
    private string $type;
    /** @var array<AdminMenu> */
    private array $children = [];

    /**
     * @param string $name
     * @param string $destination
     * @param string $displayName
     * @param string $type
     */
    public function __construct(string $name, string $destination, string $displayName, string $type)
    {
        $this->name        = $name;
        $this->destination = $destination;
        $this->displayName = $displayName;
        $this->type        = $type;
    }

    /**
     * @return array<AdminMenu>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array<AdminMenu> $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
