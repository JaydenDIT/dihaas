<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentList;

class DocumentListSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            ['document_name' => 'Death Certificate', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Termination Order of Deceased', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Age Proof Certificate (Birth Certificate or H.S.L.C)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Education Qualification Certificate', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Additional Qualification Certificate', 'document_criteria' => 'optional', 'document_type' => 'pdf'],
            ['document_name' => 'Lists of family certificate indicating DoB/Sex from SDO', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'No. of employee in the family Certificate from SDC/SDO', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Income Certificate from SOC/SDO', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'NOC from wife/husband if applicant is son/daughter (in the form of Court affidavit)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Affidavit from all eligible children if applicant is a son/daughter of deceased employee (NOC)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Undertaking from the applicant to look after the children/dependent (Affidavit)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Jamabandi Land Valuation Certificate', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Caste/Tribe Certificate', 'document_criteria' => 'caste', 'document_type' => 'pdf'],
            ['document_name' => 'Electoral Roll', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_name' => 'Police Report (if dead on duty)', 'document_criteria' => 'dead_on_duty', 'document_type' => 'pdf'],
            ['document_name' => 'Physically Handicapped Certificate (if any)', 'document_criteria' => 'handicapped', 'document_type' => 'pdf'],
            ['document_name' => 'Passport Photo', 'document_criteria' => 'compulsory', 'document_type' => 'image'],
            ['document_name' => 'Others', 'document_criteria' => 'optional', 'document_type' => 'pdf'],
        ];

        DocumentList::upsert(
            $documents,
            ['document_name'], // unique
            ['document_criteria', 'document_type'] // update these if duplicate
        );
    }
}
