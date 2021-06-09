<?php


namespace App\Controller\Admin;

use App\Service\CsvService;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class MyAbstractCrudController extends AbstractCrudController
{
    public function export(Request $request, CsvService $csvService)
    {
        $context = $request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE);

        if (method_exists($context->getEntity()->getFqcn(), 'getExportData')) {
            $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
            $filters = $this->get(FilterFactory::class)->create(
                $context->getCrud()->getFiltersConfig(),
                $fields,
                $context->getEntity()
            );
            $entities = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters)
                ->getQuery()
                ->toIterable();

            $data = [];
            foreach ($entities as $entity) {
                $data[] = $entity->getExportData();
            }

            return $csvService
                ->export(
                    $data,
                    'export_'.strtolower($context->getEntity()->getName()).'_'.date_create()->format('d-m-y').'.csv'
                );
        }
        return new Response(null, 204);
    }

    public function configureActions(Actions $actions): Actions
    {
        $class = static::getEntityFqcn();

        if (method_exists($class, 'getExportData')) {
            $export = Action::new('export', 'actions.export')
                ->setIcon('fa fa-download')
                ->linkToCrudAction('export')
                ->setCssClass('btn')
                ->createAsGlobalAction()
            ;

            return parent::configureActions($actions)->add(Crud::PAGE_INDEX, $export);
        }

        return parent::configureActions($actions);
    }
}
