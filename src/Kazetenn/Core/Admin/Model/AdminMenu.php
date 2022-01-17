<?php

namespace Kazetenn\Core\Admin\Model;

class AdminMenu
{
    private string $name;
    private string $destination;
    private string $displayName;

    /**
     * @param string $name
     * @param string $destination
     * @param string $displayName
     */
    public function __construct(string $name, string $destination, string $displayName)
    {
        $this->name        = $name;
        $this->destination = $destination;
        $this->displayName = $displayName;
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
}
