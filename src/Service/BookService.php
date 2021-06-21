<?php
/**
 * Book service.
 */

namespace App\Service;

use \App\Entity\Book;
use App\Repository\BookRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BookService.
 */
class BookService
{
    /**
     * Book repository.
     *
     * @var \App\Repository\BookRepository
     */
    private $bookRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * BookService constructor.
     *
     * @param \App\Repository\BookRepository      $bookRepository Book repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->bookRepository->queryAll(),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find by int.
     *
     * @param int $book
     *
     * @return \App\Entity\Book $book Book entity
     *
     */
    public function findByID(int $book): ?Book
    {
        return $this->bookRepository->find($book);
    }

    /**
     * Find by object.
     *
     * @param \App\Entity\Book $book Book entity
     *
     * @return \App\Entity\Book $book Book entity
     *
     */
    public function findByObject(Book $book): ?Book
    {
        return $this->bookRepository->find($book);
    }

    /**
     * Save book.
     *
     * @param \App\Entity\Book $book Book entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * Delete Book.
     *
     * @param \App\Entity\Book $book Book entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Book $book): void
    {
        $this->bookRepository->delete($book);
    }
}
