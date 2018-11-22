<?php
/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 21/11/18
 * Time: 21:59
 */

namespace App\DataFixtures;


use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    protected $faker;


    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $article = new Article();
            $article->setTitle(mb_strtolower($faker->sentence()));
            $article->setContent($faker->text());
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_' . rand(0, count(CategoryFixtures::CATEGORIES) - 1)));
        }
        $manager->flush();
    }
}