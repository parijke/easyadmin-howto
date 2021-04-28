<?php

//.....

   public function configureFields(string $pageName): iterable
    {

        if (Crud::PAGE_DETAIL === $pageName &&
            !$this->adminContextProvider->getContext()->getEntity()->getInstance()->getInsurancePolicies()->isEmpty()) {
            yield FormField::addPanel('Policies')
                ->addCssClass('no-label')
                ->renderCollapsed(true);
            yield CollectionField::new('insurancePolicies')
                ->setTemplatePath('customer-policies.html.twig')
                ->onlyOnDetail();
        }
    }
//.....
