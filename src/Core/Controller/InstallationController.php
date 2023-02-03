<?php

namespace Kazetenn\Core\Controller;

use Kazetenn\Admin\Controller\BaseAdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/installation")
 */
class InstallationController extends BaseAdminController
{
    const map = [
        'articles' => 'elvandar/kazetenn_articles',
        'pages' => 'elvandar/kazetenn_pages'
    ];

    public function indexAction(): string
    {
        return $this->renderView('@Core/page/install_index.html.twig', [
            'options' => [
                'pages'    => true,
                'articles' => true
            ],
        ]);
    }

    /**
     * @Route("/install/{bundleName}", name="install_action", methods={"GET"}, priority="1")
     */
    public function installBundle(string $bundleName, KernelInterface $kernel)
    {
        dump($bundleName);


        $phpFinder = new PhpExecutableFinder();
        $phpBin = $phpFinder->find();

        $phpExec = 'C:\Wamp.NET\bin\12-php_8.1.2_x64\php.exe';
        $composerExec = 'C:\Wamp.NET\sites\kazetenn\src\Core\Core\composer.phar';

        $process = new Process([$phpExec, $composerExec, 'show', self::map[$bundleName]]);
        $process->setWorkingDirectory("C:\Wamp.NET\sites\kazetenn");
        $process->run();

        if (!$process->isSuccessful()) {
            dd($process->getOutput());
            throw new ProcessFailedException($process);
        }

        $content = $process->getOutput();

//        $application = new Application($kernel);
//        $application->setAutoExit(false);
//
//        $input = new ArrayInput([
//            'command' => 'composer',
//        ]);
//
//        // You can use NullOutput() if you don't need the output
//        $output = new BufferedOutput();
//        $application->run($input, $output);
//
//        // return the output, don't use if you used NullOutput()
//        $content = $output->fetch();
//
//        // return new Response(""), if you used NullOutput()
        return new Response($content);
    }
}
