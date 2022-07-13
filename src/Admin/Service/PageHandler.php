<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Admin\Service;

use Exception;
use Kazetenn\Admin\Model\AdminMenu;
use Kazetenn\Admin\Model\AdminPage;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PageHandler implements ContainerAwareInterface
{
    private LoggerInterface $logger;
    use ContainerAwareTrait;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        if (null !== $container) {
            $this->container = $container;
        }
    }

    public static function buildAdminPage(string $name): AdminPage
    {
        $adminPage = new AdminPage();
        $adminPage->setName($name);
        return $adminPage;
    }

    public function getPage(string $pageName): AdminPage
    {
        /** @var array $pages */
        $pages = $this->container->getParameter('kazetenn_admin.' . AdminMenu::PAGES_ENTRIES_NAME);
        $page  = self::buildAdminPage($pageName);
        if (array_key_exists($pageName, $pages)) {
            $pageData         = $pages[$pageName];
            $data             = explode('::', $pageData['function']);
            $pageServiceName  = $data[0];
            $pageFunctionName = $data[1];

            $errors = [];
            if ($this->container->has($pageServiceName)) {
                /** @var object $pageService */
                $pageService = $this->container->get($pageServiceName);
                if (method_exists($pageService, $pageFunctionName)) {
                    $page->setServiceInfos($pageService, $pageFunctionName);
                } else {
                    $errors[] = "Service $pageServiceName does not exist or cannot be retrieved. Make sure it exist and is registered as public.";
                }
            } else {
                $errors[] = "Service $pageServiceName does not exist or cannot be retrieved. Make sure it exist and is registered as public.";
            }

            if (empty($errors)) {
                try {
                    $pageContent = $page->render();

                    if (is_string($pageContent)) {
                        $page->setContent($pageContent);
                        $page->setStatus(AdminPage::PAGE_STATUS_FOUND);
                    }
                } catch (Exception $e) {
                    $this->logger->warning("Function provided for page $pageName returner an error: \n" . $e->getMessage());
                }
            } else {
                foreach ($errors as $error) {
                    $this->logger->warning($error);
                }
            }
        } else {
            $page->setStatus(AdminPage::PAGE_STATUS_NOT_FOUND);
        }

        return $page;
    }
}
