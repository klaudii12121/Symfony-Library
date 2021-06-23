<?php
/**
 * Book service.
 */

namespace App\Service;

use \App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @var BookRepository
     */
    private BookRepository $bookRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * BookService constructor.
     *
     * @param BookRepository $bookRepository Book repository
     * @param PaginatorInterface $paginator          Paginator
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
     * @return PaginationInterface Paginated list
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
     * @return Book $book Book entity
     *
     */
    public function findByID(int $book): ?Book
    {
        return $this->bookRepository->find($book);
    }

    /**
     * Find by object.
     *
     * @param Book $book Book entity
     *
     * @return Book $book Book entity
     *
     */
    public function findByObject(Book $book): ?Book
    {
        return $this->bookRepository->find($book);
    }

    /**
     * Save book.
     *
     * @param Book $book Book entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * Delete Book.
     *
     * @param Book $book Book entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Book $book): void
    {
        $this->bookRepository->delete($book);
    }
}
