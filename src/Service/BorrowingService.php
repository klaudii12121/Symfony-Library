<?php
/**
 * Borrowing service.
 */

namespace App\Service;

use \App\Entity\Borrowing;
use App\Repository\BorrowingRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BorrowingService.
 */
class BorrowingService
{
    /**
     * Borrowing repository.
     *
     * @var BorrowingRepository
     */
    private BorrowingRepository $borrowingRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * BorrowingService constructor.
     *
     * @param BorrowingRepository $borrowingRepository Borrowing repository
     * @param PaginatorInterface $paginator Paginator
     */
    public function __construct(BorrowingRepository $borrowingRepository, PaginatorInterface $paginator)
    {
        $this->borrowingRepository = $borrowingRepository;
        $this->paginator = $paginator;
    }

    /**
     * Borrowings by User.
     *
     * @param int           $page
     * @param UserInterface $user
     *
     * @return PaginationInterface
     */
    public function borrowByUser(int $page, UserInterface $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->borrowingRepository->queryByUser($user),
            $page,
            BorrowingRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Borrowings for Admin.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function borrowForAdmin(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->borrowingRepository->queryForAdmin(),
            $page,
            BorrowingRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save borrowing.
     *
     * @param Borrowing $borrowing Borrowing entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Borrowing $borrowing): void
    {
        $this->borrowingRepository->save($borrowing);
    }

    /**
     * Delete Borrowing.
     *
     * @param Borrowing $borrowing Borrowing entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Borrowing $borrowing): void
    {
        $this->borrowingRepository->delete($borrowing);
    }
}
