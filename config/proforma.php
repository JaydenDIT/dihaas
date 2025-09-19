<?php

return [
    /**
     * ------------------------------------------------------------------------
     * Proforma Months
     * ------------------------------------------------------------------------
     * The application form submitted by citizen for Die-in-Harness will be considered valid and accepted if the date of
     * submission falls within generally 6 months (which may be configurable) after the death of the deceased person.
     * This configuration is done here.
     * 
     */
    'proforma_validity' => env('PROFORMA_VALIDITY', 6), //Only in terms of months
];
