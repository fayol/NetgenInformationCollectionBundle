<?php

namespace Netgen\Bundle\InformationCollectionBundle\Action;

use eZ\Publish\API\Repository\Repository;
use Netgen\Bundle\InformationCollectionBundle\Entity\EzInfoCollection;
use Netgen\Bundle\InformationCollectionBundle\Event\InformationCollected;
use Netgen\Bundle\InformationCollectionBundle\FieldHandler\Legacy\Registry\FieldHandlerRegistry;
use Netgen\Bundle\InformationCollectionBundle\Repository\EzInfoCollectionAttributeRepository;
use Netgen\Bundle\InformationCollectionBundle\Repository\EzInfoCollectionRepository;
use Netgen\Bundle\InformationCollectionBundle\Value\LegacyHandledFieldValue;

class PersistToDatabaseAction implements ActionInterface
{
    /**
     * @var FieldHandlerRegistry
     */
    protected $fieldHandlerRegistry;

    /**
     * @var EzInfoCollectionRepository
     */
    protected $infoCollectionRepository;

    /**
     * @var EzInfoCollectionAttributeRepository
     */
    protected $infoCollectionAttributeRepository;

    /**
     * @var Repository
     */
    protected $repository;

    public function __construct(
        FieldHandlerRegistry $fieldHandlerRegistry,
        EzInfoCollectionRepository $infoCollectionRepository,
        EzInfoCollectionAttributeRepository $infoCollectionAttributeRepository,
        Repository $repository
    )
    {
        $this->fieldHandlerRegistry = $fieldHandlerRegistry;
        $this->infoCollectionRepository = $infoCollectionRepository;
        $this->infoCollectionAttributeRepository = $infoCollectionAttributeRepository;
        $this->repository = $repository;
    }
    /**
     * @inheritDoc
     */
    public function act(InformationCollected $event)
    {
        $struct = $event->getInformationCollectionStruct();
        $contentType = $event->getContentType();
        $location = $event->getLocation();

        $content = $this->repository->getContentService()->loadContent($location->contentInfo->id);

        $currentUser = $this->repository->getCurrentUser();
        $dt = new \DateTime();

        /** @var EzInfoCollection $ezInfo */
        $ezInfo = $this->infoCollectionRepository->getInstance();

        $ezInfo->setContentObjectId($location->getContentInfo()->id);
        $ezInfo->setUserIdentifier($currentUser->login);
        $ezInfo->setCreatorId($currentUser->id);
        $ezInfo->setCreated($dt->getTimestamp());
        $ezInfo->setModified($dt->getTimestamp());

        $this->infoCollectionRepository->save($ezInfo);

        foreach($struct->getCollectedFields() as $fieldDefIdentifier => $value)
        {
            /** @var LegacyHandledFieldValue $value */
            $value = $this->fieldHandlerRegistry->handleField($value, $contentType->getFieldDefinition($fieldDefIdentifier));

            $ezInfoAttribute = $this->infoCollectionAttributeRepository->getInstance();

            $ezInfoAttribute->setContentObjectId($location->getContentInfo()->id);
            $ezInfoAttribute->setInformationCollectionId($ezInfo->getId());
            $ezInfoAttribute->setContentClassAttributeId($value->getContentClassAttributeId());
            $ezInfoAttribute->setContentObjectAttributeId($content->getField($fieldDefIdentifier)->id);
            $ezInfoAttribute->setDataInt($value->getDataInt());
            $ezInfoAttribute->setDataFloat($value->getDataFloat());
            $ezInfoAttribute->setDataText($value->getDataText());

            dump($ezInfoAttribute);

            $this->infoCollectionAttributeRepository->save($ezInfoAttribute);
        }
    }
}