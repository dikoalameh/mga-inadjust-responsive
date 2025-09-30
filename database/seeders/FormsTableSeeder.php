<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_forms')->insert([
            [
                'form_code' => 'FORM 2(A)',
                'form_name' => 'STUDY PROTOCOL REVIEW CHECKLIST',
                'form_view' => 'student/forms/form2a',
                'form_type' => 'Forms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'form_code' => 'FORM 2(B)',
                'form_name' => 'APPLICATION FOR INITIAL REVIEW',
                'form_view' => 'student/forms/form2b',
                'form_type' => 'Forms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'form_code' => 'FORM 2(C)',
                'form_name' => 'INFORMED CONSENT FORM',
                'form_view' => 'student/forms/form2c',
                'form_type' => 'Forms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'form_code' => 'FORM 2(D)',
                'form_name' => 'INFORMED CONSENT FORM FOR P.I.',
                'form_view' => 'student/forms/form2d',
                'form_type' => 'Forms',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'form_code' => 'FORM 5(E)',
                'form_name' => 'DOCUMENT HISTORY',
                'form_view' => 'student/forms/form5e',
                'form_type' => 'Forms',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'form_code' => 'FORM 2(E)',
                'form_name' => 'PROTOCOL EVALUATION CHECKLIST',
                'form_view' => 'reviewer/forms/form2e',
                'form_type' => 'Forms',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'form_code' => 'FORM 2(J)',
                'form_name' => 'INFORMED CONSENT EVALUATION CHECKLIST',
                'form_view' => 'reviewer/forms/form2j',
                'form_type' => 'Forms',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'form_code' => 'FORM 2(A) Soft Copy',
                'form_name' => 'INFORMED CONSENT EVALUATION CHECKLIST',
                'form_view' => 'student/submit-form-layout',
                'form_type' => 'Submission',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
