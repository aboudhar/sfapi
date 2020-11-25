<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{


   
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadBlogPosts($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setTitle("title one fake $i");
            $blogPost->setPublished(new \DateTime('2018-05-25'));
            $blogPost->setContent("Text one me $i");
            $blogPost->setAuthor("authorReference one $i");
            $blogPost->setSlug("slug-one-$i");
            $manager->persist($blogPost);
        }

        $manager->flush();
    }
  
}
