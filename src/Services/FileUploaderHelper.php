<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileUploaderHelper {

    private $slugger;
    private $params;

    public function __construct(SluggerInterface $slugger, ParameterBagInterface $params) {
        $this->slugger = $slugger;
        $this->params = $params;
    }

    public function uploadFile($form, $entity, $fileField): string {
        $errorMessage = "";
        $file = $form->get($fileField)->getData();

        if ($file) {
            $newFilename = uniqid(). uniqid() . uniqid() .'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->params->get('files_directory'), // Configure 'files_directory' parameter in your services.yaml file.
                    $newFilename
                );
                // Assuming you have a setPdf method in your Appointment entity.
                $entity->setPdf($newFilename);
            } catch (FileException $e) {
                $errorMessage = $e->getMessage();
            }
        }

        return $errorMessage;
    }

}
