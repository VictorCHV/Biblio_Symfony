<?php

namespace App\Repository;

use App\Entity\Book;
use App\DTO\SearchBookCriteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // Fonction affichage des 10 derniers livres, par prix décroissant
    Public function findLastTen(): array
    {
        $queryBuilder = $this->createQueryBuilder('book'); // créer la requête pour book

        return $queryBuilder-> orderBy('book.price', 'DESC')
                            -> setMaxResults(3)
                            -> getQuery() //écrire la requête
                            -> getResult(); //récupérer résultat requête
    }

    // Fonction affichage des livres de la catégorie ciblée, par prix décroissant
    Public function findTargetCategory(int $id): array
    {
        $queryBuilder = $this->createQueryBuilder('book'); // créer la requête pour book
        
        return $queryBuilder-> leftJoin('book.categories', 'category') //jointure entre livres & catégories
                            -> andWhere('category.id = :id') //condition sur l'id de la catégorie
                            -> setParameter('id', $id)// param contr les injections SQL
                            -> orderBy('book.price', 'DESC')
                            -> getQuery() //écrire la requête
                            -> getResult(); //récupérer résultat requête
    }

    public function findByCriteria(SearchBookCriteria $criteria): array
    {

        //Création du query builder
        $qb= $this->createQueryBuilder('book');

        //Filtrer les résultats selon le titre si c'est spécifié
        if($criteria->title){
            $qb->andWhere('book.title LIKE :title')
                ->setParameter('title', "%$criteria->title%");
        }

        //Filtrer les résultats par auteurs
        if(!empty($criteria->authors)){
            $qb->leftJoin('book.author', 'author')// le join est fait entre book et author
               ->andWhere('author.id IN (:authorIds)')
               ->setParameter('authorIds' , $criteria->authors);
        }

        //Filtrer les résultats par catégories
        if(!empty($criteria->categories)){
            $qb->leftJoin('book.categories', 'category')// le join est fait entre book et category
               ->andWhere('category.id IN (:catIds)')
               ->setParameter('catIds' , $criteria->categories);
        }

        //Filtrer par les prix minimals
        if($criteria->minPrice){
            $qb->andWhere('book.price >= :minPrice')
                ->setParameter('minPrice' , $criteria->minPrice);
        }

        //Filtrer par les prix maximals
        if($criteria->maxPrice){
            $qb->andWhere('book.price <= :maxPrice')
                ->setParameter('maxPrice' , $criteria->maxPrice);
        }

        //filtre par maison d'édition
        if(!empty($criteria->publishingHouses)){
            $qb 
            ->leftJoin('book.publishinghouses', 'PublishingHouse')// le join est fait entre book et publishinghouse
            ->andWhere('PublishingHouse.id IN (:publishing)')
            ->setParameter('publishing' , $criteria->publishingHouses);
        }

        $qb->orderBy("book.$criteria->orderBy" , $criteria->direction)
            ->setMaxResults($criteria->limit)
            ->setFirstResult(($criteria->page - 1) * $criteria->limit);


        return $qb ->getQuery() //ecrire la requete
                   ->getResult(); //recuperer les resultats de la requete
    }


//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
