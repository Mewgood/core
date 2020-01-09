<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula Foundation - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\Bundle\CoreInstallerBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\CoreInstallerBundle\Helper\ControllerHelper;
use Zikula\Core\Response\PlainResponse;

/**
 * Class AbstractController
 */
abstract class AbstractController
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ControllerHelper
     */
    protected $controllerHelper;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->controllerHelper = $container->get(ControllerHelper::class);
        $this->translator = $container->get(TranslatorInterface::class);
    }

    protected function renderResponse(string $view, array $parameters = [], Response $response = null): Response
    {
        if (null === $response) {
            $response = new PlainResponse();
        }
        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response;
    }
}
