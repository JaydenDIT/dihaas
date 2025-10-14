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
    /**
     * Eligibility age for applicant to apply for Die-in-Harness benefits.
     */
    'applicant_eligible_agee' => env('APPLICANT_ELIGIBLE_AGE', 15), //in years
];
