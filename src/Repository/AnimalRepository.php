<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Animal>
 *
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry,
                                private EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Animal::class);


    }

    public function findAll():array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.country', 'c')
            ->select('a.id, a.name, a.averageSize, a.averageLifespan, a.martialArt,a.phoneNumber,c.id AS countryId')
            ->getQuery()
            ->getResult();
    }

    public function findOneById($id): ?array
    {
        $result = $this->createQueryBuilder('a')
            ->leftJoin('a.country', 'c')
            ->select('a.id, a.name, a.averageSize, a.averageLifespan, a.martialArt, a.phoneNumber, c.id AS countryId')
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        $animalData = null;

        // If the query returns at least one result
        if (!empty($result)) {
            $animalData = [
                'name' => $result[0]['name'],
                'averageSize' => $result[0]['averageSize'],
                'averageLifespan' => $result[0]['averageLifespan'],
                'martialArt' => $result[0]['martialArt'],
                'phoneNumber' => $result[0]['phoneNumber'],
                'countryIds' => []
            ];

            // Collecting the country identifiers into an array
            foreach ($result as $row) {
                $animalData['countryIds'][] = $row['countryId'];
            }
        }

        return $animalData;
    }


    public function save(Animal $animal): Animal
    {
        $this->entityManager->persist($animal);
        $this->entityManager->flush();
        return $animal;
    }


    public function remove(Animal $animal): void
    {
        // Remove the specified animal from the entity manager
        $this->entityManager->remove($animal);
        // Commit the changes to the database
        $this->entityManager->flush();
    }

}
