<?php

namespace App\EventListener;


use App\Entity\Participation;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class TicketUploadListener
 */
class TicketUploadListener
{
    /**
     * @var FileUploader
     */
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
        $this->noUploadFile($entity);
    }

    /**
     * No uploaded file.
     *
     * @param $entity
     */
    private function noUploadFile($entity)
    {
        if (!$entity instanceof Participation) {
            return;
        }

        $file = $entity->getInvoice();
        if ($file instanceof File) {
            $entity->setInvoice($file->getFilename());
        }
    }

    /**
     * Upload tickets recto/verso
     *
     * @param $entity
     */
    private function uploadFile($entity)
    {
        if (!$entity instanceof Participation) {
            return;
        }

        $file = $entity->getInvoice();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $filename = $this->uploader->upload($file);
            $entity->setInvoice($filename);
        }

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Participation) {
            return;
        }

        $file = $entity->getInvoice();
        if ($file && file_exists($this->uploader->getTargetDir().$file)) {
            $entity->setInvoice(new File($this->uploader->getTargetDir().$file));
        }
    }
}