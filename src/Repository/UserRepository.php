<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function search($term)
    {
        $this->createQueryBuilder('user')
            ->andWhere('user.nom LIKE :searchTerm')
            ->orWhere('user.prenom LIKE :searchTerm')
            ->andWhere('user.id LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$term.'%')
            ->setParameter('enabled', true)
            ->getQuery()
            ->execute();
    }

    public function triecroissant()
    {
        return $this->createQueryBuilder('us')
            ->orderBy('us.nom', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function triedecroissant()
    {
        return $this->createQueryBuilder('us')
            ->orderBy('us.nom', 'DESC')
            ->getQuery()
            ->getResult();

    }

    public function CountUsers(): int
    {

        // 3. Query how many rows are there in the Articles table
        $totalArticles = $this->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // 4. Return a number as response
        // e.g 972
        return ($totalArticles);

    }
    public function CountUsersCinema(): int
    {
        $term="Cinéma";
        // 3. Query how many rows are there in the Articles table
        $totalArticles = $this->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.id)')
            ->Where('a.etat like etatTerm')
            ->setParameter('etatTerm','%'.$term.'%')
            ->getQuery()
            ->getSingleScalarResult();

        // 4. Return a number as response
        // e.g 972
        return ($totalArticles);

    }
/*
    public function CountUsersPeinture(): int
    {
        $term="Peinture";
        // 3. Query how many rows are there in the Articles table
        $totalArticles = $this->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.etat)')
            ->Where('a.etat LIKE etatTerm')
            ->setParameter('etatTerm', $term)
            ->getQuery()
            ->getSingleScalarResult();

        // 4. Return a number as response
        // e.g 972
        return ($totalArticles);

    }

    public function CountUsersPhotographie(): int
    {
        $term="Photographie";
        // 3. Query how many rows are there in the Articles table
        $totalArticles = $this->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.etat)')
            ->andWhere('a.etat = :etatTerm')
            ->setParameter('etatTerm', $term)
            ->getQuery()
            ->getSingleScalarResult();

        // 4. Return a number as response
        // e.g 972
        return ($totalArticles);

    }

    public function CountUsersCuisine(): int
    {
        $term="Cuisine";
        // 3. Query how many rows are there in the Articles table
        $totalArticles = $this->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.etat)')
            ->andWhere('a.etat = :etatTerm')
            ->setParameter('etatTerm', $term)
            ->getQuery()
            ->getSingleScalarResult();

        // 4. Return a number as response
        // e.g 972
        return ($totalArticles);

    }*/



    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
