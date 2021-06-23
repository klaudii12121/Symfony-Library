<?php
/**
 * Author service.
 */

namespace App\Service;

use App\Repository\AuthorRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AuthorService.
 */
class AuthorService
{
    /**
     * Author repository.
     *
     * @var AuthorRepository
     */
    private AuthorRepository $authorRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * BookService constructor.
     *
     * @param AuthorRepository $authorRepository Author repository
     * @param PaginatorInterface $paginator Paginator
     */
    public function __construct(AuthorRepository $authorRepository, PaginatorInterface $paginator)
    {
        $this->authorRepository = $authorRepository;
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
            $this->authorRepository->queryAll(),
            $page,
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}