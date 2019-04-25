<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Faker;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        //Créer  3 catégories
        for($i=1 ; $i<=3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph());

            $manager->persist($category);

        // créer entre 4-6 articles

            for($j=1 ; $j<= mt_rand(2, 3); $j++)
            {
                $article =new Article();
                $content= '<p>' .implode($faker->paragraphs(5), '</p><p>') .'</p>';
                $article-> setTitle($faker->sentence())
                    -> setContent($content)
                    ->setImage($faker->imageUrl($width = 640, $height = 480))
                    ->setCreatedAt($faker->dateTimeBetween('+2 years', '+3 years'))
                    ->setCategory($category);

                $manager->persist($article);


                    //Créer des commentaire pour chaque article

                    for($k=1 ; $k<= mt_rand(2, 3); $k++) {

                        $comment = new Comment();

                        $contents= '<p>' .implode($faker->paragraphs(6), '</p><p>') .'</p>';
                        $comment->setAuthor($faker->sentence())
                            ->setContent($contents)
                            ->setCreatedAt($faker->dateTimeBetween('+4 years', '+5 years'))
                            ->setArticle($article);

                        $manager->persist($comment);

                    }
            }
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
