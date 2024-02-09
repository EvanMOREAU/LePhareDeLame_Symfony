<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ServicesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $category1 = new Category;
        $category1->setName('Magnétisme');

        $category2 = new Category;
        $category2->setName('Soin Energétique');
        
        $category3 = new Category;
        $category3->setName('Hypnose');

        $service1 = new Service;
        $service1->setName('Hypnose Holistique');
        $service1->setCategory($category3);
        $service1->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service1->setDescription('A changer');
        
        $service2 = new Service;
        $service2->setName('Hypnose Régressive');
        $service2->setCategory($category3);
        $service2->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service2->setDescription('A changer');

        $service3 = new Service;
        $service3->setName('Hypnose Spirituelle');
        $service3->setCategory($category3);
        $service3->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service3->setDescription('A changer');

        $service4 = new Service;
        $service4->setName('Perle Noire');
        $service4->setCategory($category2);
        $service4->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service4->setDescription('A changer');

        $service5 = new Service;
        $service5->setName('Lahochi');
        $service5->setCategory($category2);
        $service5->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service5->setDescription('A changer');

        $service6 = new Service;
        $service6->setName('Lahochi 13ème octave');
        $service6->setCategory($category2);
        $service6->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service6->setDescription('A changer');

        $service7 = new Service;
        $service7->setName('Séance de magnétisme');
        $service7->setCategory($category1);
        $service7->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service7->setDescription('A changer');

        $service8 = new Service;
        $service8->setName('Nettoyage énergétique');
        $service8->setCategory($category1);
        $service8->setImg1('Hypnose-regressive-2-65c4c30a0ab38.png');
        $service8->setDescription('A changer');
        
        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);

        $manager->persist($service1);
        $manager->persist($service2);
        $manager->persist($service3);
        $manager->persist($service4);
        $manager->persist($service5);
        $manager->persist($service6);
        $manager->persist($service7);
        $manager->persist($service8);
        $manager->flush();
    }
}
