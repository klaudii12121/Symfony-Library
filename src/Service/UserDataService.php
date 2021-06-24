<?php
/**
 * UserData service.
 */

namespace App\Service;

use \App\Entity\UserData;
use App\Repository\UserDataRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class UserDataService.
 */
class UserDataService
{
    /**
     * UserData repository.
     *
     * @var UserDataRepository
     */
    private UserDataRepository $userDataRepository;

    /**
     * UserService constructor.
     *
     * @param UserDataRepository $userDataRepository UserData repository
     */
    public function __construct(UserDataRepository $userDataRepository)
    {
        $this->userDataRepository = $userDataRepository;
    }

    /**
     * Find one by id.
     *
     * @param int $id
     *
     * @return UserData $userData UserData entity
     */
    public function find(int $id): ?UserData
    {
        return $this->userDataRepository->find($id);
    }

    /**
     * Save User.
     *
     * @param UserData $userData UserData entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserData $userData): void
    {
        $this->userDataRepository->save($userData);
    }
}
