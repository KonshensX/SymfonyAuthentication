<?php

namespace BlogBundle\Security;

use AppBundle\Entity\User;
use BlogBundle\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter {
    
    // This is a const for the sake if the IDE and me
    const EDIT = 'edit';
    const VIEW = 'view';
    const DELETE = 'delete';


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
        // So in here i need to check whether the attribute actually exists or not
        // I Need to return true if they exists and false if they don't
        // In this one i'm checking if they don't exist and return false
        if (!in_array($attribute, [self::DELETE, self::EDIT, self::VIEW])) {
            return false;
        }

        // I need to check if the subject passed is of type post
        // Return false if the subject is not of type Post
        if (!$subject instanceof Post) {
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
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // I Need the user to be logged in in order to proceed
        if (!$user instanceof User) {
            return false;
        }

        // I Need to check if the user trying to delete the Post is the owner
        if (!$user == $subject->getUser()) {
            return false;
        }

        return true;

    }
}