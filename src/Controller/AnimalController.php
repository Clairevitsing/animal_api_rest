<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Manager\AnimalManager;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Illuminate\Support\Facades\Log;



#[Route('api/animals')]
class AnimalController extends AbstractController
{

    public function __construct(private AnimalRepository $animalRepository,
    private readonly AnimalManager $animalManager)
    {
    }

    #[Route('/', name: 'app_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): JsonResponse
    {
        $animals = $animalRepository->findAll();
        //dd($animals);
        return $this->json($animals, context: ['groups' => 'animal:read']);
    }

    #[Route('/{id}', name: 'app_animal_read', methods: ['GET'])]
    public function read(int $id, AnimalRepository $animalRepository): JsonResponse
    {
        $animal = $animalRepository->findOneById($id);
        if (!$animal) {
            throw $this->createNotFoundException('Animal not found');
        }
        //dd($animal);
        return $this->json($animal, context: ['groups' => 'animal:read']);
    }

    #[Route('/new', name: 'app_animal_create', methods: ['POST'])]
    public function create(Request $request, AnimalRepository $animalRepository): JsonResponse
    {
        try {
            // Retrieve the data sent from Postman
            $data = json_decode($request->getContent(), true);

            // Validation of required data
            $requiredFields = ['name', 'averageSize', 'averageLifespan', 'martialArt', 'phoneNumber', 'countryIds'];
            foreach ($requiredFields as $field) {
                // Check if each required field is present in the data
                if (!isset($data[$field])) {
                    // If any required field is missing, throw a Bad Request exception
                    return new JsonResponse(['message' => "Missing required field: $field"], Response::HTTP_BAD_REQUEST);
                }
            }

            // Call the create method of AnimalRepository to create the animal
            $animal = $this->animalManager->create($data);

            // Return a JSON response indicating successful creation of the animal
            return new JsonResponse($animalRepository->findOneById($animal->getId()), Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], $exception->getCode());
        }
    }

    #[Route('/{id}', name: 'app_animal_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request,AnimalRepository $animalRepository): JsonResponse
    {
        // Retrieve the animal to edit using the AnimalRepository
        $animal = $animalRepository->find($id);


        // Check if the animal exists
        if (!$animal instanceof Animal) {
            // If the animal is not found, return a JSON response with an error message
            return new JsonResponse(['message' => 'Animal not found'], Response::HTTP_NOT_FOUND);
        }

        //dd($animal);
        // Retrieve the data sent from Postman
        $data = json_decode($request->getContent(), true);
        //dd($data);

        // Call the update method in the animal manager to update the animal
        try {
            $updatedAnimal = $this->animalManager->update($animal,$data);
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exceptions
            return new JsonResponse(['message' => $e->getMessage()], $e->getCode());
        }

        // Return a JSON response indicating success
        return new JsonResponse($animalRepository->findOneById($updatedAnimal->getId()), Response::HTTP_OK);
    }


    #[Route('/{id}', name: 'app_animal_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request, AnimalRepository $animalRepository, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the animal to delete using the AnimalRepository
        $animal = $animalRepository->find($id);

        // Check if the animal exists
        if (!$animal) {
            return new JsonResponse(['message' => 'Animal not found'], Response::HTTP_NOT_FOUND);
        }

        // Use the repository's remove method to delete the animal
        $animalRepository->remove($animal);

        // Return a JSON response indicating success
        return new JsonResponse(['message' => 'Animal is deleted successfully'], Response::HTTP_OK);
    }

}


