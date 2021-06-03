<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Parameter;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends AbstratctRepository implements PasswordUpgraderInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByEmail($email): ?User
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select(['u'])
            ->from(User::class, 'u')
            ->where(
                $queryBuilder->expr()->eq('u.email', ':x'),
            )
            ->setParameters(new ArrayCollection([
                new Parameter('x', $email),
            ]))
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
