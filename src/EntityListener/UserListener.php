<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener {

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function prePersist(User $user) 
    {
        $this->encodePassword($user);
    }
    public function preUpdate(User $user) 
    {
        $this->encodePassword($user);
    }
    /**
     * Encode passeword based on plain password
     *
     * @param User $user
     * @return void
     */
    public function encodePassword(User $user) 
    {
        // on verifie si le password est vide
        if($user->getPlainPassword() === null) {
            return;
        }
        $user->setPassword(
            $this->hasher->hashPassword(
               $user,
               $user->getPlainPassword() 
            )
        );

        $user->setPlainPassword('null', null); // ce n'est pas obligatoire
    }       
}
