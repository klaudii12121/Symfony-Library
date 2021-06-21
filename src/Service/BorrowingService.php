<?php
/**
 * Borrowing service.
 */

namespace App\Service;

use \App\Entity\Borrowing;
use App\Repository\BorrowingRepository;
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
     * @var \App\Repository\BorrowingRepository
     */
    private $borrowingRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * BorrowingService constructor.
     *
     * @param \App\Repository\BorrowingRepository      $borrowingRepository Borrowing repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(BorrowingRepository $borrowingRepository, PaginatorInterface $paginator)
    {
        $this->borrowingRepository = $borrowingRepository;
        $this->paginator = $paginator;
    }

    /**
     * Borrowings by User.
     *
     * @param UserInterface $user
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
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
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
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
     * @param \App\Entity\Borrowing $borrowing Borrowing entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Borrowing $borrowing): void
    {
        $this->borrowingRepository->save($borrowing);
    }

    /**
     * Delete Borrowing.
     *
     * @param \App\Entity\Borrowing $borrowing Borrowing entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Borrowing $borrowing): void
    {
        $this->borrowingRepository->delete($borrowing);
    }
}
