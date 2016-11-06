<?php

namespace Netgen\Bundle\InformationCollectionBundle\Form\Builder;

use eZ\Publish\API\Repository\ContentTypeService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use eZ\Publish\API\Repository\Values\Content\Location;
use Netgen\Bundle\EzFormsBundle\Form\DataWrapper;
use Netgen\Bundle\EzFormsBundle\Form\Payload\InformationCollectionStruct;

class FormBuilder
{
    const EZFORMS_INFORMATION_COLLECTION = 'ezforms_information_collection';

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var boolean
     */
    protected $useCsrf;

    /**
     * @var ContentTypeService
     */
    protected $contentTypeService;

    /**
     * FormBuilder constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param ContentTypeService $contentTypeService
     * @param boolean $useCsrf
     */
    public function __construct(FormFactoryInterface $formFactory, ContentTypeService $contentTypeService, $useCsrf)
    {
        $this->formFactory = $formFactory;
        $this->useCsrf = $useCsrf;
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * Creates Information collection Form object for given Location object
     *
     * @param Location $location
     *
     * @return FormInterface
     */
    public function createFormForLocation(Location $location)
    {
        $contentInfo = $location->contentInfo;

        $contentType = $this->contentTypeService->loadContentType( $contentInfo->contentTypeId );

        $data = new DataWrapper( new InformationCollectionStruct(), $contentType, $location );

        $formBuilder = $this->formFactory
            ->createBuilder(
                self::EZFORMS_INFORMATION_COLLECTION,
                $data,
                [
                    'csrf_protection' => $this->useCsrf,
                ]
            );

        return $formBuilder->getForm();
    }
}