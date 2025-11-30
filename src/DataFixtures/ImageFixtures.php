<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $images = [
            [
                'url' => 'https://cdn.pixabay.com/photo/2018/05/18/15/30/webdesign-3411373_960_720.jpg',
                'alt' => 'Cloud Computing',
                'ref' => 'image_cloud'
            ],
            [
                'url' => 'https://cdn.pixabay.com/photo/2016/11/19/14/00/code-1839406_960_720.jpg',
                'alt' => 'Cyber Security',
                'ref' => 'image_security'
            ],
            [
                'url' => 'https://cdn.pixabay.com/photo/2015/02/05/08/06/mac-624707_960_720.jpg',
                'alt' => 'Mobile Development',
                'ref' => 'image_mobile'
            ]
        ];

        foreach ($images as $imageData) {
            $image = new Image();
            $image->setUrl($imageData['url']);
            $image->setAlt($imageData['alt']);
            $manager->persist($image);
            $this->addReference($imageData['ref'], $image);
        }

        $manager->flush();
    }
}