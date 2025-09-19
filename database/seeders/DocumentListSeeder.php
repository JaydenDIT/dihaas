<?php

namespace Database\Seeders;

use App\Library\Database\AutoIncrement;
use Illuminate\Database\Seeder;
use App\Models\DocumentList;
use Illuminate\Support\Facades\DB;

class DocumentListSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            ['document_list_id' => 1, 'document_name' => 'Death Certificate', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 2, 'document_name' => 'Termination Order of Deceased', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 3, 'document_name' => 'Age Proof Certificate (Birth Certificate or H.S.L.C)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 4, 'document_name' => 'Education Qualification Certificate', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 5, 'document_name' => 'Additional Qualification Certificate', 'document_criteria' => 'optional', 'document_type' => 'pdf'],
            ['document_list_id' => 6, 'document_name' => 'Lists of family certificate indicating DoB/Sex from SDO', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 7, 'document_name' => 'No. of employee in the family Certificate from SDC/SDO', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 8, 'document_name' => 'Income Certificate from SOC/SDO', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 9, 'document_name' => 'NOC from wife/husband if applicant is son/daughter (in the form of Court affidavit)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 10, 'document_name' => 'Affidavit from all eligible children if applicant is a son/daughter of deceased employee (NOC)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 11, 'document_name' => 'Undertaking from the applicant to look after the children/dependent (Affidavit)', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 12, 'document_name' => 'Jamabandi Land Valuation Certificate', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 13, 'document_name' => 'Caste/Tribe Certificate', 'document_criteria' => 'caste', 'document_type' => 'pdf'],
            ['document_list_id' => 14, 'document_name' => 'Electoral Roll', 'document_criteria' => 'compulsory', 'document_type' => 'pdf'],
            ['document_list_id' => 15, 'document_name' => 'Police Report (if dead on duty)', 'document_criteria' => 'dead_on_duty', 'document_type' => 'pdf'],
            ['document_list_id' => 16, 'document_name' => 'Physically Handicapped Certificate (if any)', 'document_criteria' => 'handicapped', 'document_type' => 'pdf'],
            ['document_list_id' => 17, 'document_name' => 'Passport Photo', 'document_criteria' => 'compulsory', 'document_type' => 'image'],
            ['document_list_id' => 18, 'document_name' => 'Others', 'document_criteria' => 'optional', 'document_type' => 'pdf'],
        ];

        DocumentList::upsert(
            $documents,
            ['document_list_id'], // unique
            ['document_name', 'document_criteria', 'document_type'] // update these if duplicate
        );

        AutoIncrement::resetIndex('document_list', 'document_list_id');
    }
}
