<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentList;

class DocumentListSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            ['Death Certificate', 'compulsory', 'pdf'],
            ['Termination Order of Deceased', 'compulsory', 'pdf'],
            ['Age Proof Certificate (Birth Certificate or H.S.L.C)', 'compulsory', 'pdf'],
            ['Education Qualification Certificate', 'compulsory', 'pdf'],
            ['Additional Qualification Certificate', 'optional', 'pdf'],
            ['Lists of family certificate indicating DoB/Sex from SDO', 'compulsory', 'pdf'],
            ['No. of employee in the family Certificate from SDC/SDO', 'compulsory', 'pdf'],
            ['Income Certificate from SOC/SDO', 'compulsory', 'pdf'],
            ['NOC from wife/husband if applicant is son/daughter (in the form of Court affidavit)', 'compulsory', 'pdf'],
            ['Affidavit from all eligible children if applicant is a son/daughter of deceased employee (NOC)', 'compulsory', 'pdf'],
            ['Undertaking from the applicant to look after the children/dependent (Affidavit)', 'compulsory', 'pdf'],
            ['Jamabandi Land Valuation Certificate', 'compulsory', 'pdf'],
            ['Caste/Tribe Certificate', 'caste', 'pdf'],
            ['Electoral Roll', 'compulsory', 'pdf'],
            ['Police Report (if dead on duty)', 'dead_on_duty', 'pdf'],
            ['Physically Handicapped Certificate (if any)', 'handicapped', 'pdf'],
            ['Passport Photo', 'compulsory', 'jpg'],
            ['Others', 'optional', 'pdf'],
        ];

        foreach ($documents as $doc) {
            DocumentList::create([
                'document_name'     => $doc[0],
                'document_criteria' => $doc[1],
                'document_type'     => $doc[2]
            ]);
        }
    }
}
