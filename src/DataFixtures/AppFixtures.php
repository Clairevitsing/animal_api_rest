<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Animal;
use App\Entity\Country;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    const NBCOUNTRIES = 10;
    const NBANIMALS = 20;


    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $countries = [];
        for ($i = 0; $i < self::NBCOUNTRIES; $i++) {
            $country = new Country();
            $country
                ->setName($faker->name())
                ->setISOCode($faker->countryCode());

            $manager->persist($country);

            $counties[] = $country;
        }

        $animals = [];
        for ($i = 0; $i < self::NBANIMALS; $i++) {

            $animal = new Animal();
            $animal
                ->setName($faker->firstName())
                ->setAverageSize($faker->numberBetween(1, 100))
                ->setAverageLifespan($faker->numberBetween(1, 20))
                ->setMartialArt($faker->randomElement(['Karate', 'Judo', 'Taekwondo']))
                ->setPhoneNumber($faker->phoneNumber())
                ->addCountry($counties[rand(0, self::NBCOUNTRIES-1)]);

            $manager->persist($animal);
            $animals[] = $animal;
        }

        $manager->flush();
    }
}