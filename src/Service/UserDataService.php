<?php
/**
 * UserData service.
 */

namespace App\Service;

use \App\Entity\UserData;
use App\Repository\UserDataRepository;

/**
 * Class UserDataService.
 */
class UserDataService
{
    /**
     * UserData repository.
     *
     * @var \App\Repository\UserDataRepository
     */
    private $userDataRepository;

    /**
     * UserService constructor.
     *
     * @param \App\Repository\UserDataRepository      $userDataRepository UserData repository
     */
    public function __construct(UserDataRepository $userDataRepository)
    {
        $this->userDataRepository = $userDataRepository;
    }


    /**
     * Save User.
     *
     * @param \App\Entity\UserData $userData UserData entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UserData $userData): void
    {
        $this->userDataRepository->save($userData);
    }
}
