<?php

namespace App\Manager;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\Response;

class AnimalManager
{
    public function __construct(
        private readonly AnimalRepository $animalRepository,
        private readonly CountryRepository $countryRepository
    )
    {
    }

    public function create(array $data): Animal
    {
        $animal = new Animal();
        //dd($data, $animal);
        $countryIds = $data['countryIds'];
        foreach ($countryIds as $countryId) {
            // Get a reference to the Country entity based on the ID
            $country = $this->countryRepository->find($countryId);
            if (!$country) {
                throw new \InvalidArgumentException('Country entity not found', Response::HTTP_BAD_REQUEST);
            }
            // Add the Country entity reference to the animal's collection of countries
            $animal->addCountry($country);
        }

        $animal->setName($data['name']);
        $animal->setAverageSize($data['averageSize']);
        $animal->setAverageLifespan($data['averageLifespan']);
        $animal->setMartialArt($data['martialArt']);
        $animal->setPhoneNumber($data['phoneNumber']);

        return $this->animalRepository->save($animal);
    }

    public function update(Animal $animal, array $data): Animal
    {
        $animal->setName($data['name']?? $animal->getName());
        $animal->setAverageSize($data['averageSize']?? $animal->getAverageSize());
        $animal->setAverageLifespan($data['averageLifespan']?? $animal->getAverageLifespan());
        $animal->setMartialArt($data['martialArt']?? $animal->getMartialArt());
        $animal->setPhoneNumber($data['phoneNumber']?? $animal->getPhoneNumber());

        $animal->getCountry()->clear();

        // Retrieve 'countryId' from the data
        $countryIds = $data['countryIds'];
        //dd($countryIds) ;

        // Iterate over each 'countryId' in the array
        foreach ($countryIds as $countryId) {
            // Find the country entity using its ID
            $country = $this->countryRepository->find($countryId);

            // Check if the country entity is found
            if (!$country) {
                throw new \InvalidArgumentException('Country entity not found for ID: ' . $countryId, Response::HTTP_BAD_REQUEST);
            }

            // Add the country to the animal
            $animal->addCountry($country);
        }

        return $this->animalRepository->save($animal);
    }


}