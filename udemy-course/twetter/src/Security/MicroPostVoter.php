<?php


namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\LogicException;

/***
 * Class MicroPostVoter
 * @package App\Security
 */
class MicroPostVoter extends Voter
{
//    TODO: define view voter.
    const EDIT = 'edit';
    const DELETE = 'delete';
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
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
     * @param MicroPost $microPost
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $microPost, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        $user = $token->getUser();
        if ($user instanceof User) {
            switch ($attribute) {
                case self::DELETE:
                    return $this->canDelete($microPost, $user);
                case self::EDIT:
                    return $this->canEdit($microPost, $user);
            }
        } else {
            return false;
        }
        throw  new LogicException("Something is wrong!");
    }

    private function canDelete(MicroPost $microPost, User $user)
    {
        return $microPost->getUser()->getId() === $user->getId();
    }

    private function canEdit(MicroPost $microPost, User $user)
    {
        return $microPost->getUser()->getId() === $user->getId();
    }
}