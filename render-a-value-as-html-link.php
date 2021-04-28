<?php

//....


   yield TextField::new('address.shortAddress')
                ->formatValue(
                    function ($value) {
                        return sprintf(
                            '<a href="https://www.google.com/maps/place/%s" target="_blank">%s</a>',
                            $value,
                            $value
                        );
                    }
                )
                ->renderAsHtml(true)
                ->hideOnForm()
                ->setLabel('Address')

//....
