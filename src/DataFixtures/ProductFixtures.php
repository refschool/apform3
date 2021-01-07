<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    protected $em;
    protected $slugger;

    public function __construct(EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        //$category = $this->em->getRepository(Category::class)->find(1);
        $category1 = $manager->getRepository(Category::class)->find(1);
        $category2 = $manager->getRepository(Category::class)->find(2);

        for ($i = 0; $i < 8; $i++) {
            $product = new Product();

            $nameArray = ["Iphone 9", "Iphone 10", "Iphone 11", "Iphone 12", "Samsung S9", "Samsung S10", "Samsung S20", "Samsung S21", "Samsung S22", "Samsung S23",];
            $imageArray = ["iphone-7.jpg", "toto2.jpeg", "toto-5fd3977c3c5f1.jpeg", "xiaomi.jpg", "huawei-p20.jpg", "iphone-7.jpg", "toto2.jpeg", "toto-5fd3977c3c5f1.jpeg", "xiaomi.jpg", "huawei-p20.jpg",];

            $randNameIndex = random_int(0, count($nameArray) - 1);
            $name = $nameArray[$randNameIndex];

            $randImageIndex = random_int(0, count($imageArray) - 1);
            $image = $imageArray[$randImageIndex];

            $price = random_int(10000, 20000);
            $slug = $this->slugger->slug($name);

            $category = substr($name, 0, 1) === 'I' ? $category1 : $category2;

            //on enlève les éléments du tableau
            unset($nameArray[$randNameIndex]);
            unset($imageArray[$randImageIndex]);

            $product->setName($name)
                ->setPrice($price)
                ->setSlug($slug)
                ->setImg($image)
                ->setCategory($category);

            $manager->persist($product);
        }


        // $product->setName('Iphone 13')
        //     ->setPrice(10000)
        //     ->setSlug('iphone-13')
        //     ->setImg('velib5-5ff337e21c3a8.jpeg')
        //     ->setCategory($category);


        // générer un 10aine produits un peu au hasard
        // prix au hasard entre 10000 et 20000
        // un tableau de nom ["Iphone", "Samsung S"]
        // un tableau d'indice ["9","10","11", "12"]
        // un tableau de fichier image ["image1.jpg","image2.jpg","image3.jpg","image4.jpg","image5.png"]

        //faire une boucle for pour générer des produits dans la table product
        // avec association au hasard des valeurs



        //fin du for


        $manager->flush();
    }
}
