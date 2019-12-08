<?php


namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MicroPostVoter extends Voter
{

    const EDIT = 'edit';
    const DELTE = 'delete';

    /**
     * Determines if the attribute/action and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::DELTE, self::EDIT])) {
            return false;
        }
        if (!$subject instanceof MicroPost) {
            return false;
        }
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // checking the actual permissions.
        $authenticatedUser = $token->getUser();
        if (!$authenticatedUser instanceof User) {
            return false;
        }

        /* @var MicroPost $subject */
        return $subject->getUser()->getId() === $authenticatedUser->getId();
    }
}