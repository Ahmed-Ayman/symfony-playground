<?php


namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/***
 * Class MicroPostVoter
 * @package App\Security
 */
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
        if (!$subject instanceof MicroPost)
            return false;

        return true; // we need to check permissions of this item.
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
    protected function voteOnAttribute($attribute, $microPost, TokenInterface $token)
    {
        // checking the actual permissions.
        $authenticatedUser = $token->getUser();
        if (!$authenticatedUser instanceof User) {
            return false;
        }

        /* @var MicroPost $subject */
        return $microPost->getUser()->getId() === $authenticatedUser->getId();
    }

    private function canDelete(MicroPost $microPost, User $user)
    {
        if ($microPost->getUser()->getId() === $user->getId()) {
            return true;
        } else {
            throw  new AccessDeniedException("Fuck you can't delete");
        }

    }

    private function canEdit(MicroPost $microPost, User $user)
    {

        if ($microPost->getUser()->getId() === $user->getId()) {
            return true;
        } else {
            throw  new AccessDeniedException("Fuck you can't edit");
        }
    }
}