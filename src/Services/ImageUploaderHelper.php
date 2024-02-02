<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageUploaderHelper {

    private $slugger;
    private $params;

    public function __construct(SluggerInterface $slugger, ParameterBagInterface $params) {
        $this->slugger = $slugger;
        $this->params = $params;
    }

    public function uploadImage($form, $service, $img): string {
        $errorMessage = "";
        $imageFile = $form->get($img)->getData();

        if ($imageFile) {
            // $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($service->getName());
            // $safeFilename = $service->getName();
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->params->get('images_directory'),
                    $newFilename
                );
                if ($img == 'img1') {
                    $service->setImg1($newFilename);
                } elseif ($img == 'img2') {
                    $service->setImg2($newFilename);
                } elseif ($img == 'img3') {
                    $service->setImg3($newFilename);
                }
            } catch (FileException $e) {
                $errorMessage = $e->getMessage();
            }
        }

        return $errorMessage;
    }

}
