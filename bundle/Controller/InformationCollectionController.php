<?php

namespace Netgen\Bundle\InformationCollectionBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\View\CachableView;
use eZ\Publish\Core\MVC\Symfony\View\ContentValueView;
use Netgen\InformationCollection\API\InformationCollectionTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InformationCollectionController implements ContainerAwareInterface
{
    use InformationCollectionTrait;
    use ContainerAwareTrait;

    /**
     * Displays and handles information collection.
     *
     * @param ContentValueView $view
     * @param Request $request
     *
     * @return ContentValueView
     */
    public function displayAndHandle(ContentValueView $view, Request $request)
    {
        $parameters = $this->collectInformation($view, $request);

        $view->addParameters($parameters);

        if ($view instanceof CachableView) {
            $view->setCacheEnabled(false);
        }

        return $view;
    }


    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
