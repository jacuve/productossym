<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class ProductoRepository extends ServiceEntityRepository
{
    public const ITEMS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    public function save(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActivos(int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.activo = :activo')
            ->setParameter('activo', true)
            ->orderBy('p.nombre', 'ASC')
            ->getQuery();

        return $this->paginate($query, $page);
    }

    public function findAllOrderedByStock(int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.activo = :activo')
            ->setParameter('activo', true)
            ->orderBy('p.stock', 'ASC')
            ->getQuery();

        return $this->paginate($query, $page);
    }

    public function buscarPorNombre(string $termino, int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.nombre LIKE :termino')
            ->orWhere('p.codigo LIKE :termino')
            ->setParameter('termino', '%' . $termino . '%')
            ->orderBy('p.nombre', 'ASC')
            ->getQuery();

        return $this->paginate($query, $page);
    }

    public function buscarPorNombreAll(string $termino): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nombre LIKE :termino')
            ->orWhere('p.codigo LIKE :termino')
            ->setParameter('termino', '%' . $termino . '%')
            ->orderBy('p.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategoria(string $categoria, int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.categoria = :categoria')
            ->andWhere('p.activo = :activo')
            ->setParameter('categoria', $categoria)
            ->setParameter('activo', true)
            ->orderBy('p.nombre', 'ASC')
            ->getQuery();

        return $this->paginate($query, $page);
    }

    public function findStockBajo(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.stock <= p.stock_minimo')
            ->andWhere('p.activo = :activo')
            ->setParameter('activo', true)
            ->orderBy('p.stock', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function paginate($query, int $page): Paginator
    {
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(self::ITEMS_PER_PAGE * ($page - 1))
            ->setMaxResults(self::ITEMS_PER_PAGE);

        return $paginator;
    }
}
