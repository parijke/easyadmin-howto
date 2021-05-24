<?php


    // New action called duplicate
    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new('duplicate', false)
            ->setIcon('fa fa-copy')
            ->linkToUrl(
                fn (Todo $todo) => $this->adminUrlGenerator // I use it for copying todo entities
                    ->setAction(Action::EDIT)
                    ->setEntityId($todo->getId())
                    ->set('duplicate', '1') // here the duplicate action is set on the url
                    ->generateUrl()
            );
        $actions
            ->add(Crud::PAGE_INDEX, $duplicate);

        return parent::configureActions($actions);
    }

    // override the edit fuction to handle the duplication 
    // the duplicated record is shown in the form before saving
    public function edit(AdminContext $context)
    {
        if ($context->getRequest()->query->has('duplicate')) {
            $entity = $context->getEntity()->getInstance();
            $cloned = clone $entity; // beware of setting the right __clone things in your entity
            $cloned->setCreatedAt(new \DateTime('now'));
            $context->getEntity()->setInstance($cloned);
        }

        return parent::edit($context);
    }
}
