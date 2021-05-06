<?php

// This can be done by changing the choices via setFormTypeOptions 
// on the Field in configureFields. I've put an example below that 
// checks if user roles is not ROLE_ADMIN if not it will only show 
// the allowed choices, this seems to work just the way I want it to.

// Took a bit of guesswork and digging since this wasn't clearly explained in the docs.

public function configureFields(string $pageName): iterable
{
    $fields = [];
    if (array_search('ROLE_ADMIN', $this->getUser()->getRoles()) === false) {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'username' => $this->getUser()->getUsername()
        ]);
        if ($user) {
            $fields[] = AssociationField::new('suppliers')->onlyOnForms()->setFormTypeOptions([
                "choices" => $user->getEnabledSuppliers()->toArray()
            ]);
        }
    } else {
        $fields[] = AssociationField::new('suppliers')->onlyOnForms();
    }
    return $fields;
}