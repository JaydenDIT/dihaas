<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentList;

class DocumentListSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            ['Death Certificate', 'compulsory'],
            ['Termination Order of Deceased', 'compulsory'],
            ['Age Proof Certificate (Birth Certificate or H.S.L.C)', 'compulsory'],
            ['Education Qualification Certificate', 'compulsory'],
            ['Additional Qualification Certificate', 'optional'],
            ['Lists of family certificate indicating DoB/Sex from SDO', 'compulsory'],
            ['No. of employee in the family Certificate from SDC/SDO', 'compulsory'],
            ['Income Certificate from SOC/SDO', 'compulsory'],
            ['NOC from wife/husband if applicant is son/daughter (in the form of Court affidavit)', 'compulsory'],
            ['Affidavit from all eligible children if applicant is a son/daughter of deceased employee (NOC)', 'compulsory'],
            ['Undertaking from the applicant to look after the children/dependent (Affidavit)', 'compulsory'],
            ['Jamabandi Land Valuation Certificate', 'compulsory'],
            ['Caste/Tribe Certificate', 'caste'],
            ['Electoral Roll', 'compulsory'],
            ['Police Report (if dead on duty)', 'dead_on_duty'],
            ['Physically Handicapped Certificate (if any)', 'handicapped'],
            ['Others', 'optional'],
        ];

        foreach ($documents as $doc) {
            DocumentList::create([
                'document_name'     => $doc[0],
                'document_criteria' => $doc[1],
                'document_type'     => 'pdf', // default, can be changed per document
            ]);
        }
    }
}
