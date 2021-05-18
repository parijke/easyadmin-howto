
//
// In this case we use an actual field, jusy because EA needs it.
// But then we render a value based on a service calculation.

    yield IntegerField::new('id')
        ->setLabel('Days before next')
        ->setVirtual(true)
        ->hideOnForms(true) // Important because otherwise EA tries to call the setters
        ->formatValue(
            function ($value, $entity) {
                if (null === $entity) {
                    return -1;
                }

                return $this->bookingService->daysBeforeNextBooking($entity);
            });
