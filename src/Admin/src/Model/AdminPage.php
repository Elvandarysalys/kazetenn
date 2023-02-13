<?php

namespace Kazetenn\Admin\Model;

class AdminPage
{
    const PAGE_STATUS_NOT_FOUND = 0;
    const PAGE_STATUS_FOUND     = 1;

    protected ?object $service  = null;
    protected ?string $function = null;
    protected ?string $content  = null;
    protected ?string $name     = null;
    protected ?int    $status   = self::PAGE_STATUS_NOT_FOUND;
    protected array   $errors   = [];

    public function setServiceInfos(object $service, string $function): AdminPage
    {
        $this->setService($service);
        $this->setFunction($function);
        return $this;
    }

    /**
     * @return false|string
     */
    public function render()
    {
        if (null === $this->service || null === $this->function) {
            return false;
        }
        $function = $this->function;
        return $this->service->$function();
    }

    /**
     * @return object|null
     */
    public function getService(): ?object
    {
        return $this->service;
    }

    /**
     * @return string|null
     */
    public function getFunction(): ?string
    {
        return $this->function;
    }

    /**
     * @param object|null $service
     */
    public function setService(?object $service): void
    {
        $this->service = $service;
    }

    /**
     * @param string|null $function
     */
    public function setFunction(?string $function): void
    {
        $this->function = $function;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $error
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }
}
