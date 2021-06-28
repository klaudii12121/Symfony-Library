<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class TagService.
 */
class TagService
{
    /**
     * Tag repository.
     *
     * @var TagRepository
     */
    private TagRepository $tagRepository;

    /**
     * TagService constructor.
     *
     * @param TagRepository $tagRepository Tag repository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Find tag by Id.
     *
     * @param int $id Tag Id
     *
     * @return Tag|null Tag entity
     */
    public function findById(int $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }

    /**
     * Save tag.
     *
     * @param Tag $tag Tag entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Delete tag.
     *
     * @param Tag $tag Tag entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

    /**
     * Find by name.
     *
     * @param string $TagName
     *
     * @return Tag|null Tag entity
     */
    public function findOneByTagName(string $tagName): ?Tag
    {
        return $this->tagRepository->findOneByTagName($tagName);
    }
}