<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductoRepository extends ServiceEntityRepository
{
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

    public function findActivos(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.activo = :activo')
            ->setParameter('activo', true)
            ->orderBy('p.nombre', 'ASC')
            ->getQuery()
            ->getResult();
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

    public function buscarPorNombre(string $termino): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nombre LIKE :termino')
            ->orWhere('p.codigo LIKE :termino')
            ->setParameter('termino', '%' . $termino . '%')
            ->orderBy('p.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategoria(string $categoria): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categoria = :categoria')
            ->andWhere('p.activo = :activo')
            ->setParameter('categoria', $categoria)
            ->setParameter('activo', true)
            ->orderBy('p.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedByStock(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.activo = :activo')
            ->setParameter('activo', true)
            ->orderBy('p.stock', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
