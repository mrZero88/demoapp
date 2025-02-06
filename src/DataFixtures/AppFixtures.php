<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product;
        $product->setName('Product 1');
        $product->setDescription('Description of product 1');
        $product->setSize(100);

        $manager->persist($product);

        $product = new Product;
        $product->setName('Product 2');
        $product->setDescription('Description of product 2');
        $product->setSize(200);

        $manager->persist($product);

        $manager->flush();
    }
}
