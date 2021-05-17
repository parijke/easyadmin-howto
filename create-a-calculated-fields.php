
//
// In this case we use an actual field, jusy because EA needs it.
// But then we render a value based on a service calculation.

    yield IntegerField::new('id')
        ->setLabel('Days before next')
        ->setVirtual(true)
        ->formatValue(
            function ($value, $entity) {
                if (null !== $entity) {
                    return $this->bookingService->daysBeforeNextBooking($entity);
                } else {
                    return -1;
                }
            });
