<?php

declare(strict_types=1);

namespace Netgen\InformationCollection\API\Value\DataTransfer;

use eZ\Publish\API\Repository\Values\Content\Content;
use Netgen\InformationCollection\API\Value\Event\InformationCollected;
use Netgen\InformationCollection\API\Value\ValueObject;
use Twig_TemplateWrapper;

class TemplateContent extends ValueObject
{
    /**
     * @var InformationCollected
     */
    protected $event;

    /**
     * @var Content
     */
    protected $content;

    /**
     * @var Twig_TemplateWrapper
     */
    protected $templateWrapper;

    /**
     * TemplateData constructor.
     *
     * @param InformationCollected $event
     * @param Content $content
     * @param Twig_TemplateWrapper $templateWrapper
     */
    public function __construct(InformationCollected $event, Content $content, Twig_TemplateWrapper $templateWrapper)
    {
        $this->event = $event;
        $this->content = $content;
        $this->templateWrapper = $templateWrapper;
    }

    /**
     * @return InformationCollected
     */
    public function getEvent(): \Netgen\InformationCollection\API\Value\Event\InformationCollected
    {
        return $this->event;
    }

    /**
     * @return Content
     */
    public function getContent(): \eZ\Publish\API\Repository\Values\Content\Content
    {
        return $this->content;
    }

    /**
     * @return Twig_TemplateWrapper
     */
    public function getTemplateWrapper(): \Twig_TemplateWrapper
    {
        return $this->templateWrapper;
    }
}
