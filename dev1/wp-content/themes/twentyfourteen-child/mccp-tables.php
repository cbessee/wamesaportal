<?php

add_action('frm_after_create_entry', 'create_mccp_enrollment', 20, 2);
function create_mccp_enrollment($entry_id, $form_id){
  if($form_id == 74){ //change 4 to the form id of the form to copy
    global $wpdb;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mccp_campus' => $_POST['item_meta'][4065], 'enroll_yr' => $_POST['item_meta'][4066], 'frst_academic_yr' => $_POST['item_meta'][4067], 'mccp_classification' => $_POST['item_meta'][4214], 'running_start_student' => $_POST['item_meta'][3661], 'veteran' => $_POST['item_meta'][3662], 'ssn' => $_POST['item_meta'][3663], 'student_id_number' => $_POST['item_meta'][3664], 'first_name' => $_POST['item_meta'][3665], 'middle_initial' => $_POST['item_meta'][3666], 'last_name' => $_POST['item_meta'][3667], 'perm_address' => $_POST['item_meta'][3668], 'perm_city' => $_POST['item_meta'][3669], 'perm_state' => $_POST['item_meta'][4040], 'perm_zip' => $_POST['item_meta'][3671], 'perm_country' => $_POST['item_meta'][4069], 'cur_address' => $_POST['item_meta'][3672], 'cur_city' => $_POST['item_meta'][3673], 'cur_state' => $_POST['item_meta'][4042], 'cur_zip' => $_POST['item_meta'][3675], 'cur_country' => $_POST['item_meta'][4070], 'home_phone' => $_POST['item_meta'][3692], 'work_phone' => $_POST['item_meta'][3693], 'cell_phone' => $_POST['item_meta'][3694], 'primary_email' => $_POST['item_meta'][3695], 'email_other' => $_POST['item_meta'][3696], 'dob' => $_POST['item_meta'][3676], 'lang_other_than_english' => $_POST['item_meta'][4086], 'specify_languages' => $_POST['item_meta'][3683], 'gender' => $_POST['item_meta'][4084], 'ethnicity' => $_POST['item_meta'][4081], 'race' => $_POST['item_meta'][4082], 'tribal_affiliation' => $_POST['item_meta'][3681], 'multiracial' => $_POST['item_meta'][3679], 'father_highest_lvl_educ' => $_POST['item_meta'][3684], 'father_other_educ' => $_POST['item_meta'][3685], 'mother_highest_lvl_educ' => $_POST['item_meta'][3686], 'mother_other_educ' => $_POST['item_meta'][3687], 'father_work_type' => $_POST['item_meta'][3688], 'father_other_work' => $_POST['item_meta'][3689], 'mother_work_type' => $_POST['item_meta'][3690], 'mother_other_work' => $_POST['item_meta'][3691], 'social_media' => $_POST['item_meta'][4068], 'res_status' => $_POST['item_meta'][4072], 'qtr_first_enrolled' => $_POST['item_meta'][3699], 'first_yr_enrolled' => $_POST['item_meta'][3700], 'current_advisor' => $_POST['item_meta'][3701], 'college_credits_completed' => $_POST['item_meta'][3702], 'cur_cum_gpa' => $_POST['item_meta'][3703], 'major_choices' => $_POST['item_meta'][4073], 'highest_math_lvl' => $_POST['item_meta'][4087], 'cur_courses' => $_POST['item_meta'][4080], 'int_txfr_univ' => $_POST['item_meta'][4074], 'proj_txfr_qtr' => $_POST['item_meta'][4075], 'proj_txfr_yr' => $_POST['item_meta'][4076], 'prev_high_school' => $_POST['item_meta'][3718], 'hs_grad_yr' => $_POST['item_meta'][4077], 'prev_colleges' => $_POST['item_meta'][4078], 'obstacles' => $_POST['item_meta'][4079], 'currently_employed' => $_POST['item_meta'][3716], 'work_hrs_week' => $_POST['item_meta'][3717], 'notes' => $_POST['item_meta'][3734], 'student_permission' => $_POST['item_meta'][3735], 'parental_permission' => $_POST['item_meta'][3737], 'user_id' => $_POST['item_meta'][3653], 'updated_by' => $_POST['item_meta'][4063], 'parent_post_id' => $entry_id, 'date_frm_created' => $_POST['item_meta'][3654], 'time_frm_created' => $_POST['item_meta'][3655], 'ip_address' => $_POST['item_meta'][3656], 'ptitle' => $_POST['item_meta'][4062], 'student_key' => $_POST['item_meta'][4154], 'post_type' => 'post');
    $wpdb->insert('wp_mccp_enrollment', $values);
  }
}

add_action('frm_after_update_entry', 'update_mccp_enrollment', 20, 2);
function update_mccp_enrollment($entry_id, $form_id){
  if($form_id == 74){ //change 4 to the form id of the form to copy
    global $wpdb;
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM wp_mccp_enrollment WHERE entry_id = %s AND post_type != 'post'", $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mccp_campus' => $_POST['item_meta'][4065], 'enroll_yr' => $_POST['item_meta'][4066], 'frst_academic_yr' => $_POST['item_meta'][4067], 'mccp_classification' => $_POST['item_meta'][4214], 'running_start_student' => $_POST['item_meta'][3661], 'veteran' => $_POST['item_meta'][3662], 'ssn' => $_POST['item_meta'][3663], 'student_id_number' => $_POST['item_meta'][3664], 'first_name' => $_POST['item_meta'][3665], 'middle_initial' => $_POST['item_meta'][3666], 'last_name' => $_POST['item_meta'][3667], 'perm_address' => $_POST['item_meta'][3668], 'perm_city' => $_POST['item_meta'][3669], 'perm_state' => $_POST['item_meta'][4040], 'perm_zip' => $_POST['item_meta'][3671], 'perm_country' => $_POST['item_meta'][4069], 'cur_address' => $_POST['item_meta'][3672], 'cur_city' => $_POST['item_meta'][3673], 'cur_state' => $_POST['item_meta'][4042], 'cur_zip' => $_POST['item_meta'][3675], 'cur_country' => $_POST['item_meta'][4070], 'home_phone' => $_POST['item_meta'][3692], 'work_phone' => $_POST['item_meta'][3693], 'cell_phone' => $_POST['item_meta'][3694], 'primary_email' => $_POST['item_meta'][3695], 'email_other' => $_POST['item_meta'][3696], 'dob' => $_POST['item_meta'][3676], 'lang_other_than_english' => $_POST['item_meta'][4086], 'specify_languages' => $_POST['item_meta'][3683], 'gender' => $_POST['item_meta'][4084], 'ethnicity' => $_POST['item_meta'][4081], 'race' => $_POST['item_meta'][4082], 'tribal_affiliation' => $_POST['item_meta'][3681], 'multiracial' => $_POST['item_meta'][3679], 'father_highest_lvl_educ' => $_POST['item_meta'][3684], 'father_other_educ' => $_POST['item_meta'][3685], 'mother_highest_lvl_educ' => $_POST['item_meta'][3686], 'mother_other_educ' => $_POST['item_meta'][3687], 'father_work_type' => $_POST['item_meta'][3688], 'father_other_work' => $_POST['item_meta'][3689], 'mother_work_type' => $_POST['item_meta'][3690], 'mother_other_work' => $_POST['item_meta'][3691], 'social_media' => $_POST['item_meta'][4068], 'res_status' => $_POST['item_meta'][4072], 'qtr_first_enrolled' => $_POST['item_meta'][3699], 'first_yr_enrolled' => $_POST['item_meta'][3700], 'current_advisor' => $_POST['item_meta'][3701], 'college_credits_completed' => $_POST['item_meta'][3702], 'cur_cum_gpa' => $_POST['item_meta'][3703], 'major_choices' => $_POST['item_meta'][4073], 'highest_math_lvl' => $_POST['item_meta'][4087], 'cur_courses' => $_POST['item_meta'][4080], 'int_txfr_univ' => $_POST['item_meta'][4074], 'proj_txfr_qtr' => $_POST['item_meta'][4075], 'proj_txfr_yr' => $_POST['item_meta'][4076], 'prev_high_school' => $_POST['item_meta'][3718], 'hs_grad_yr' => $_POST['item_meta'][4077], 'prev_colleges' => $_POST['item_meta'][4078], 'obstacles' => $_POST['item_meta'][4079], 'currently_employed' => $_POST['item_meta'][3716], 'work_hrs_week' => $_POST['item_meta'][3717], 'notes' => $_POST['item_meta'][3734], 'student_permission' => $_POST['item_meta'][3735], 'parental_permission' => $_POST['item_meta'][3737], 'user_id' => $_POST['item_meta'][3653], 'updated_by' => $_POST['item_meta'][4063], 'parent_post_id' => $_POST['item_meta'][4356], 'date_frm_created' => $_POST['item_meta'][3654], 'time_frm_created' => $_POST['item_meta'][3655], 'ip_address' => $_POST['item_meta'][3656], 'ptitle' => $_POST['item_meta'][4062], 'student_key' => $_POST['item_meta'][4154], 'post_type' => $post_type);
    $wpdb->insert('wp_mccp_enrollment', $values);
  }
}

add_action('frm_after_create_entry', 'create_mccp_facultyadvisor', 20, 2);
function create_mccp_facultyadvisor($entry_id, $form_id){
  if($form_id == 76){ //change 4 to the form id of the form to copy
    global $wpdb;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mesa_center' => $_POST['item_meta'][4219], 'first_name' => $_POST['item_meta'][3933], 'middle_initial' => $_POST['item_meta'][3934], 'last_name' => $_POST['item_meta'][3935], 'home_address' => $_POST['item_meta'][3936], 'home_city' => $_POST['item_meta'][3937], 'home_state' => $_POST['item_meta'][3938], 'home_zip' => $_POST['item_meta'][3939], 'advisor_daytime_phone' => $_POST['item_meta'][3940], 'advisor_evening_phone' => $_POST['item_meta'][3941], 'advisor_email_address' => $_POST['item_meta'][3942], 'allow_call_at_home' => $_POST['item_meta'][3943], 'best_time_to_call' => $_POST['item_meta'][4220], 'mccp_campus' => $_POST['item_meta'][4221], 'campus_address' => $_POST['item_meta'][3946], 'campus_city' => $_POST['item_meta'][3947], 'campus_state' => $_POST['item_meta'][3948], 'campus_zip' => $_POST['item_meta'][3949], 'campus_phone_number' => $_POST['item_meta'][3950], 'campus_fax' => $_POST['item_meta'][3951], 'department_dean' => $_POST['item_meta'][3952], 'emer_cntct_name' => $_POST['item_meta'][3953], 'emer_cntct_daytime_phone' => $_POST['item_meta'][3954], 'emer_cntct_evening_phone' => $_POST['item_meta'][3955], 'emer_cntct_relationship' => $_POST['item_meta'][3957], 'emer_cntct_address' => $_POST['item_meta'][3956], 'emer_cntct_city' => $_POST['item_meta'][4191], 'emer_cntct_state' => $_POST['item_meta'][4194], 'emer_cntct_zip' => $_POST['item_meta'][4192], 'college_major' => $_POST['item_meta'][3958], 'college_minor' => $_POST['item_meta'][3959], 'grad_yr' => $_POST['item_meta'][3961], 'teaching_certification' => $_POST['item_meta'][3962], 'college_degrees' => $_POST['item_meta'][4222], 'when_became_advisor' => $_POST['item_meta'][3963], 'when_started_teaching' => $_POST['item_meta'][3964], 'how_many_events' => $_POST['item_meta'][3965], 'receive_stipend_support' => $_POST['item_meta'][3966], 'receive_release_time' => $_POST['item_meta'][3967], 'when_is_prep_period' => $_POST['item_meta'][3968], 'list_math_lvls' => $_POST['item_meta'][3970], 'list_sci_classes' => $_POST['item_meta'][3971], 'other_skills' => $_POST['item_meta'][3972], 'want_to_attend_training' => $_POST['item_meta'][3973], 'attend_what_workshops' => $_POST['item_meta'][3976], 'want_to_present' => $_POST['item_meta'][3974], 'what_to_present' => $_POST['item_meta'][3975], 'user_id' => $_POST['item_meta'][3977], 'updated_by' => $_POST['item_meta'][4215], 'date_frm_created' => $_POST['item_meta'][4278], 'time_frm_created' => $_POST['item_meta'][4279], 'ip_address' => $_POST['item_meta'][4280], 'ptitle' => $_POST['item_meta'][4216], 'advisor_key' => $_POST['item_meta'][4218], 'post_type' => 'post');
    $wpdb->insert('wp_mccp_facultyadvisor', $values);
  }
}

add_action('frm_after_update_entry', 'update_mccp_facultyadvisor', 20, 2);
function update_mccp_facultyadvisor($entry_id, $form_id){
  if($form_id == 76){ //change 4 to the form id of the form to copy
    global $wpdb;
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM wp_mccp_facultyadvisor WHERE entry_id = %s AND post_type != 'post'", $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mesa_center' => $_POST['item_meta'][4219], 'first_name' => $_POST['item_meta'][3933], 'middle_initial' => $_POST['item_meta'][3934], 'last_name' => $_POST['item_meta'][3935], 'home_address' => $_POST['item_meta'][3936], 'home_city' => $_POST['item_meta'][3937], 'home_state' => $_POST['item_meta'][3938], 'home_zip' => $_POST['item_meta'][3939], 'advisor_daytime_phone' => $_POST['item_meta'][3940], 'advisor_evening_phone' => $_POST['item_meta'][3941], 'advisor_email_address' => $_POST['item_meta'][3942], 'allow_call_at_home' => $_POST['item_meta'][3943], 'best_time_to_call' => $_POST['item_meta'][4220], 'mccp_campus' => $_POST['item_meta'][4221], 'campus_address' => $_POST['item_meta'][3946], 'campus_city' => $_POST['item_meta'][3947], 'campus_state' => $_POST['item_meta'][3948], 'campus_zip' => $_POST['item_meta'][3949], 'campus_phone_number' => $_POST['item_meta'][3950], 'campus_fax' => $_POST['item_meta'][3951], 'department_dean' => $_POST['item_meta'][3952], 'emer_cntct_name' => $_POST['item_meta'][3953], 'emer_cntct_daytime_phone' => $_POST['item_meta'][3954], 'emer_cntct_evening_phone' => $_POST['item_meta'][3955], 'emer_cntct_relationship' => $_POST['item_meta'][3957], 'emer_cntct_address' => $_POST['item_meta'][3956], 'emer_cntct_city' => $_POST['item_meta'][4191], 'emer_cntct_state' => $_POST['item_meta'][4194], 'emer_cntct_zip' => $_POST['item_meta'][4192], 'college_major' => $_POST['item_meta'][3958], 'college_minor' => $_POST['item_meta'][3959], 'grad_yr' => $_POST['item_meta'][3961], 'teaching_certification' => $_POST['item_meta'][3962], 'college_degrees' => $_POST['item_meta'][4222], 'when_became_advisor' => $_POST['item_meta'][3963], 'when_started_teaching' => $_POST['item_meta'][3964], 'how_many_events' => $_POST['item_meta'][3965], 'receive_stipend_support' => $_POST['item_meta'][3966], 'receive_release_time' => $_POST['item_meta'][3967], 'when_is_prep_period' => $_POST['item_meta'][3968], 'list_math_lvls' => $_POST['item_meta'][3970], 'list_sci_classes' => $_POST['item_meta'][3971], 'other_skills' => $_POST['item_meta'][3972], 'want_to_attend_training' => $_POST['item_meta'][3973], 'attend_what_workshops' => $_POST['item_meta'][3976], 'want_to_present' => $_POST['item_meta'][3974], 'what_to_present' => $_POST['item_meta'][3975], 'user_id' => $_POST['item_meta'][3977], 'updated_by' => $_POST['item_meta'][4215], 'date_frm_created' => $_POST['item_meta'][4278], 'time_frm_created' => $_POST['item_meta'][4279], 'ip_address' => $_POST['item_meta'][4280], 'ptitle' => $_POST['item_meta'][4216], 'advisor_key' => $_POST['item_meta'][4218], 'post_type' => $post_type);
    $wpdb->insert('wp_mccp_facultyadvisor', $values);
  }
}


add_action('frm_after_create_entry', 'create_mccp_exit', 20, 2);
function create_mccp_exit($entry_id, $form_id){
  if($form_id == 75){ //change 4 to the form id of the form to copy
    global $wpdb;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mccp_campus' => $_POST['item_meta'][4168], 'interviewer_name' => $_POST['item_meta'][3842], 'yr_first_joined_mesa' => $_POST['item_meta'][3843], 'ssn' => $_POST['item_meta'][4162], 'student_id_number' => $_POST['item_meta'][3841], 'exit_frm_dt' => $_POST['item_meta'][3840], 'first_name' => $_POST['item_meta'][3845], 'middle_initial' => $_POST['item_meta'][4157], 'last_name' => $_POST['item_meta'][3844], 'perm_address' => $_POST['item_meta'][3846], 'perm_city' => $_POST['item_meta'][3847], 'perm_state' => $_POST['item_meta'][3848], 'perm_zip' => $_POST['item_meta'][3849], 'perm_country' => $_POST['item_meta'][4169], 'cur_address' => $_POST['item_meta'][3850], 'cur_city' => $_POST['item_meta'][3851], 'cur_state' => $_POST['item_meta'][3852], 'cur_zip' => $_POST['item_meta'][3853], 'cur_country' => $_POST['item_meta'][4170], 'home_phone' => $_POST['item_meta'][3854], 'work_phone' => $_POST['item_meta'][4161], 'cell_phone' => $_POST['item_meta'][3855], 'email' => $_POST['item_meta'][3856], 'email_other' => $_POST['item_meta'][3857], 'dob' => $_POST['item_meta'][3858], 'lang_other_than_english' => $_POST['item_meta'][3863], 'other_languages' => $_POST['item_meta'][3864], 'gender' => $_POST['item_meta'][3859], 'ethnicity' => $_POST['item_meta'][4171], 'race' => $_POST['item_meta'][4172], 'tribal_affiliation' => $_POST['item_meta'][3862], 'multiracial' => $_POST['item_meta'][4008], 'qtr_first_enrolled' => $_POST['item_meta'][4173], 'yr_first_enrolled' => $_POST['item_meta'][4174], 'reason_for_enrolling' => $_POST['item_meta'][3869], 'highest_lvl_educ' => $_POST['item_meta'][3868], 'check_if_you' => $_POST['item_meta'][4175], 'school_name' => $_POST['item_meta'][3870], 'hs_grad_yr' => $_POST['item_meta'][4177], 'school_city' => $_POST['item_meta'][3871], 'school_state' => $_POST['item_meta'][3872], 'school_zip' => $_POST['item_meta'][4164], 'prev_colleges' => $_POST['item_meta'][4176], 'last_qtr_in_cc' => $_POST['item_meta'][4225], 'last_yr_in_cc' => $_POST['item_meta'][4226], 'total_units_completed' => $_POST['item_meta'][3877], 'total_transferrable_units' => $_POST['item_meta'][3878], 'plans_when_you_leave' => $_POST['item_meta'][3879], 'other_plans' => $_POST['item_meta'][3880], 'intended_txfr_univ' => $_POST['item_meta'][3881], 'intended_txfr_dept' => $_POST['item_meta'][3882], 'intended_degree' => $_POST['item_meta'][3883], 'intended_degree_other' => $_POST['item_meta'][3884], 'financial_aid_elig' => $_POST['item_meta'][3885], 'est_college_cost' => $_POST['item_meta'][3886], 'scholarship_money_available' => $_POST['item_meta'][3887], 'hrs_per_week_working' => $_POST['item_meta'][3888], 'transferring_with_family' => $_POST['item_meta'][3889], 'does_finaid_cover_expenses' => $_POST['item_meta'][3890], 'intended_cc_enrollment' => $_POST['item_meta'][3891], 'reason_for_enrolling_at_other_cc' => $_POST['item_meta'][3892], 'reason_for_withdrawal' => $_POST['item_meta'][4227], 'reason_for_withdrawal_other' => $_POST['item_meta'][3894], 'cur_employed' => $_POST['item_meta'][3895], 'cur_employer' => $_POST['item_meta'][3896], 'cur_employer_other' => $_POST['item_meta'][3897], 'cur_job_stem_related' => $_POST['item_meta'][3898], 'mesa_programs' => $_POST['item_meta'][4228], 'used_study_center' => $_POST['item_meta'][3900], 'other_programs_interest' => $_POST['item_meta'][4230], 'activity_interests_other' => $_POST['item_meta'][3927], 'internship_participation' => $_POST['item_meta'][3902], 'specify_internship' => $_POST['item_meta'][3903], 'conference_participation' => $_POST['item_meta'][3904], 'specify_conference' => $_POST['item_meta'][3905], 'other_program_participation' => $_POST['item_meta'][3906], 'specify_other_program_participation' => $_POST['item_meta'][3907], 'see_guest_speakers' => $_POST['item_meta'][3908], 'guest_speaker_event' => $_POST['item_meta'][3909], 'volunteer' => $_POST['item_meta'][3910], 'specify_volunteer' => $_POST['item_meta'][3911], 'club_participation' => $_POST['item_meta'][3912], 'club_position_yr' => $_POST['item_meta'][3913], 'university_tours' => $_POST['item_meta'][3914], 'workshops' => $_POST['item_meta'][3915], 'leadership_retreat' => $_POST['item_meta'][3916], 'year-end_banquet' => $_POST['item_meta'][3917], 'college_recruiter' => $_POST['item_meta'][3918], 'professional_society' => $_POST['item_meta'][3919], 'work_for_mesa' => $_POST['item_meta'][3920], 'what_did_you_do_at_mesa' => $_POST['item_meta'][4231], 'mesa_work_other' => $_POST['item_meta'][3922], 'mesa_work_hrs' => $_POST['item_meta'][3923], 'how_heard_about_mesa' => $_POST['item_meta'][4229], 'referring_faculty' => $_POST['item_meta'][4234], 'referring_counselor' => $_POST['item_meta'][4235], 'referring_student' => $_POST['item_meta'][3925], 'specify_other_presentation' => $_POST['item_meta'][4282], 'how_can_we_improve' => $_POST['item_meta'][3928], 'allow_to_contact' => $_POST['item_meta'][3929], 'willing_to_participate' => $_POST['item_meta'][4232], 'parent_post_id' => $_POST['item_meta'][3838], 'user_id' => $_POST['item_meta'][3931], 'updated_by' => $_POST['item_meta'][4155], 'date_frm_created' => $_POST['item_meta'][4272], 'time_frm_created' => $_POST['item_meta'][4273], 'ip_address' => $_POST['item_meta'][4274], 'student_key' => $_POST['item_meta'][4167], 'ptitle' => $_POST['item_meta'][4223], 'post_type' => 'post');
    $wpdb->insert('wp_mccp_exit', $values);

    // update has_exit_frm in parent record
    $data = array('meta_value' => 'Yes');
    $where = array('item_id' => $_POST['item_meta'][3838], 'field_id' => '4353');
    $wpdb->update($frmdb->entry_metas, $data, $where);

    // update latest enrollment entry so has_exit_frm is yes
    // first get the latest created_at date
    $sql = $wpdb->prepare("SELECT max(created_at) FROM wp_mccp_enrollment WHERE entry_id = %s", $_POST['item_meta'][3838]);
    $max_dt = $wpdb->get_var($sql);
    // now that we have the latest enrollment entry, we can update the table
    $data = array('has_exit_frm' => 'Yes');
    $where = array('entry_id' => $_POST['item_meta'][3838], 'created_at' => $max_dt);
    $wpdb->update('wp_mccp_enrollment', $data, $where);
  }
}

add_action('frm_after_update_entry', 'update_mccp_exit', 20, 2);
function update_mccp_exit($entry_id, $form_id){
  if($form_id == 75){ //change 4 to the form id of the form to copy
    global $wpdb;
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM wp_mccp_exit WHERE entry_id = %s AND post_type != 'post'", $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mccp_campus' => $_POST['item_meta'][4168], 'interviewer_name' => $_POST['item_meta'][3842], 'yr_first_joined_mesa' => $_POST['item_meta'][3843], 'ssn' => $_POST['item_meta'][4162], 'student_id_number' => $_POST['item_meta'][3841], 'exit_frm_dt' => $_POST['item_meta'][3840], 'first_name' => $_POST['item_meta'][3845], 'middle_initial' => $_POST['item_meta'][4157], 'last_name' => $_POST['item_meta'][3844], 'perm_address' => $_POST['item_meta'][3846], 'perm_city' => $_POST['item_meta'][3847], 'perm_state' => $_POST['item_meta'][3848], 'perm_zip' => $_POST['item_meta'][3849], 'perm_country' => $_POST['item_meta'][4169], 'cur_address' => $_POST['item_meta'][3850], 'cur_city' => $_POST['item_meta'][3851], 'cur_state' => $_POST['item_meta'][3852], 'cur_zip' => $_POST['item_meta'][3853], 'cur_country' => $_POST['item_meta'][4170], 'home_phone' => $_POST['item_meta'][3854], 'work_phone' => $_POST['item_meta'][4161], 'cell_phone' => $_POST['item_meta'][3855], 'email' => $_POST['item_meta'][3856], 'email_other' => $_POST['item_meta'][3857], 'dob' => $_POST['item_meta'][3858], 'lang_other_than_english' => $_POST['item_meta'][3863], 'other_languages' => $_POST['item_meta'][3864], 'gender' => $_POST['item_meta'][3859], 'ethnicity' => $_POST['item_meta'][4171], 'race' => $_POST['item_meta'][4172], 'tribal_affiliation' => $_POST['item_meta'][3862], 'multiracial' => $_POST['item_meta'][4008], 'qtr_first_enrolled' => $_POST['item_meta'][4173], 'yr_first_enrolled' => $_POST['item_meta'][4174], 'reason_for_enrolling' => $_POST['item_meta'][3869], 'highest_lvl_educ' => $_POST['item_meta'][3868], 'check_if_you' => $_POST['item_meta'][4175], 'school_name' => $_POST['item_meta'][3870], 'hs_grad_yr' => $_POST['item_meta'][4177], 'school_city' => $_POST['item_meta'][3871], 'school_state' => $_POST['item_meta'][3872], 'school_zip' => $_POST['item_meta'][4164], 'prev_colleges' => $_POST['item_meta'][4176], 'last_qtr_in_cc' => $_POST['item_meta'][4225], 'last_yr_in_cc' => $_POST['item_meta'][4226], 'total_units_completed' => $_POST['item_meta'][3877], 'total_transferrable_units' => $_POST['item_meta'][3878], 'plans_when_you_leave' => $_POST['item_meta'][3879], 'other_plans' => $_POST['item_meta'][3880], 'intended_txfr_univ' => $_POST['item_meta'][3881], 'intended_txfr_dept' => $_POST['item_meta'][3882], 'intended_degree' => $_POST['item_meta'][3883], 'intended_degree_other' => $_POST['item_meta'][3884], 'financial_aid_elig' => $_POST['item_meta'][3885], 'est_college_cost' => $_POST['item_meta'][3886], 'scholarship_money_available' => $_POST['item_meta'][3887], 'hrs_per_week_working' => $_POST['item_meta'][3888], 'transferring_with_family' => $_POST['item_meta'][3889], 'does_finaid_cover_expenses' => $_POST['item_meta'][3890], 'intended_cc_enrollment' => $_POST['item_meta'][3891], 'reason_for_enrolling_at_other_cc' => $_POST['item_meta'][3892], 'reason_for_withdrawal' => $_POST['item_meta'][4227], 'reason_for_withdrawal_other' => $_POST['item_meta'][3894], 'cur_employed' => $_POST['item_meta'][3895], 'cur_employer' => $_POST['item_meta'][3896], 'cur_employer_other' => $_POST['item_meta'][3897], 'cur_job_stem_related' => $_POST['item_meta'][3898], 'mesa_programs' => $_POST['item_meta'][4228], 'used_study_center' => $_POST['item_meta'][3900], 'other_programs_interest' => $_POST['item_meta'][4230], 'activity_interests_other' => $_POST['item_meta'][3927], 'internship_participation' => $_POST['item_meta'][3902], 'specify_internship' => $_POST['item_meta'][3903], 'conference_participation' => $_POST['item_meta'][3904], 'specify_conference' => $_POST['item_meta'][3905], 'other_program_participation' => $_POST['item_meta'][3906], 'specify_other_program_participation' => $_POST['item_meta'][3907], 'see_guest_speakers' => $_POST['item_meta'][3908], 'guest_speaker_event' => $_POST['item_meta'][3909], 'volunteer' => $_POST['item_meta'][3910], 'specify_volunteer' => $_POST['item_meta'][3911], 'club_participation' => $_POST['item_meta'][3912], 'club_position_yr' => $_POST['item_meta'][3913], 'university_tours' => $_POST['item_meta'][3914], 'workshops' => $_POST['item_meta'][3915], 'leadership_retreat' => $_POST['item_meta'][3916], 'year-end_banquet' => $_POST['item_meta'][3917], 'college_recruiter' => $_POST['item_meta'][3918], 'professional_society' => $_POST['item_meta'][3919], 'work_for_mesa' => $_POST['item_meta'][3920], 'what_did_you_do_at_mesa' => $_POST['item_meta'][4231], 'mesa_work_other' => $_POST['item_meta'][3922], 'mesa_work_hrs' => $_POST['item_meta'][3923], 'how_heard_about_mesa' => $_POST['item_meta'][4229], 'referring_faculty' => $_POST['item_meta'][4234], 'referring_counselor' => $_POST['item_meta'][4235], 'referring_student' => $_POST['item_meta'][3925], 'specify_other_presentation' => $_POST['item_meta'][4282], 'how_can_we_improve' => $_POST['item_meta'][3928], 'allow_to_contact' => $_POST['item_meta'][3929], 'willing_to_participate' => $_POST['item_meta'][4232], 'parent_post_id' => $_POST['item_meta'][3838], 'user_id' => $_POST['item_meta'][3931], 'updated_by' => $_POST['item_meta'][4155], 'date_frm_created' => $_POST['item_meta'][4272], 'time_frm_created' => $_POST['item_meta'][4273], 'ip_address' => $_POST['item_meta'][4274], 'student_key' => $_POST['item_meta'][4167], 'ptitle' => $_POST['item_meta'][4223], 'post_type' => $post_type);
    $wpdb->insert('wp_mccp_exit', $values);
  }
}

add_action('frm_after_create_entry', 'create_mccp_activities', 20, 2);
function create_mccp_activities($entry_id, $form_id){
  if($form_id == 73){ //change 4 to the form id of the form to copy
    global $wpdb;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'student_activities' => $_POST['item_meta'][4248], 'aew_facilitator_courses' => $_POST['item_meta'][4249], 'aew_participant_courses' => $_POST['item_meta'][4250], 'internships' => $_POST['item_meta'][4251], 'scholarships' => $_POST['item_meta'][4252], 'mesa_tutor_courses' => $_POST['item_meta'][4253], 'parent_post_id' => $_POST['item_meta'][3645], 'user_id' => $_POST['item_meta'][3652], 'updated_by' => $_POST['item_meta'][4244], 'date_frm_created' => $_POST['item_meta'][4266], 'time_frm_created' => $_POST['item_meta'][4267], 'ip_address' => $_POST['item_meta'][4268], 'student_key' => $_POST['item_meta'][4246], 'ptitle' => $_POST['item_meta'][4247], 'post_type' => 'post');
    $wpdb->insert('wp_mccp_activities', $values);

    // update parent form has_activities_frm value to yes
    $data = array('meta_value' => 'Yes');
    $where = array('item_id' => $_POST['item_meta'][3645], 'field_id' => '4352');
    $wpdb->update($frmdb->entry_metas, $data, $where);

    // update latest enrollment entry so has_activities_frm is yes
    // first get the latest created_at date
    $sql = $wpdb->prepare("SELECT max(created_at) FROM wp_mccp_enrollment WHERE entry_id = %s", $_POST['item_meta'][3645]);
    $max_dt = $wpdb->get_var($sql);
    // now that we have the latest enrollment entry, we can update the table
    $data = array('has_activities_frm' => 'Yes');
    $where = array('entry_id' => $_POST['item_meta'][3645], 'created_at' => $max_dt);
    $wpdb->update('wp_mccp_enrollment', $data, $where);
  }
}

add_action('frm_after_update_entry', 'update_mccp_activities', 20, 2);
function update_mccp_activities($entry_id, $form_id){
  if($form_id == 73){ //change 4 to the form id of the form to copy
    global $wpdb;
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM wp_mccp_activities WHERE entry_id = %s AND post_type != 'post'", $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'student_activities' => $_POST['item_meta'][4248], 'aew_facilitator_courses' => $_POST['item_meta'][4249], 'aew_participant_courses' => $_POST['item_meta'][4250], 'internships' => $_POST['item_meta'][4251], 'scholarships' => $_POST['item_meta'][4252], 'mesa_tutor_courses' => $_POST['item_meta'][4253], 'parent_post_id' => $_POST['item_meta'][3645], 'user_id' => $_POST['item_meta'][3652], 'updated_by' => $_POST['item_meta'][4244], 'date_frm_created' => $_POST['item_meta'][4266], 'time_frm_created' => $_POST['item_meta'][4267], 'ip_address' => $_POST['item_meta'][4268], 'student_key' => $_POST['item_meta'][4246], 'ptitle' => $_POST['item_meta'][4247], 'post_type' => $post_type);
    $wpdb->insert('wp_mccp_activities', $values);
  }
}

add_action('frm_after_create_entry', 'create_mccp_persistence', 20, 2);
function create_mccp_persistence($entry_id, $form_id){
  if($form_id == 77){ //change 4 to the form id of the form to copy
    global $wpdb;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'persistence_classification' => $_POST['item_meta'][4243], 'persistence_term' => $_POST['item_meta'][4236], 'term' => $_POST['item_meta'][3981], 'academic_year' => $_POST['item_meta'][4237], 'persistence_date' => $_POST['item_meta'][3980], 'persistence_status' => $_POST['item_meta'][4238], 'persistence_gpa' => $_POST['item_meta'][3987], 'persistence_cumulative_gpa' => $_POST['item_meta'][3988], 'transferred_where' => $_POST['item_meta'][4183], 'explanation_of_status' => $_POST['item_meta'][3984], 'persistence_major' => $_POST['item_meta'][4239], 'was_aew_offered' => $_POST['item_meta'][4180], 'did_student_take_aew' => $_POST['item_meta'][4181], 'courses' => $_POST['item_meta'][4240], 'comments' => $_POST['item_meta'][4179], 'parent_post_id' => $_POST['item_meta'][3978], 'user_id' => $_POST['item_meta'][3990], 'updated_by' => $_POST['item_meta'][4184], 'date_frm_created' => $_POST['item_meta'][4260], 'time_frm_created' => $_POST['item_meta'][4261], 'ip_address' => $_POST['item_meta'][4262], 'ptitle' => $_POST['item_meta'][4241], 'student_key' => $_POST['item_meta'][4185], 'post_type' => 'post');
    $wpdb->insert('wp_mccp_persistence', $values);

    // update has_persistence_frm in parent record
    $data = array('meta_value' => 'Yes');
    $where = array('item_id' => $_POST['item_meta'][3978], 'field_id' => '4351');
    $wpdb->update($frmdb->entry_metas, $data, $where);

    // update latest enrollment entry so has_persistence_frm is yes
    // first get the latest created_at date
    $sql = $wpdb->prepare("SELECT max(created_at) FROM wp_mccp_enrollment WHERE entry_id = %s", $_POST['item_meta'][3978]);
    $max_dt = $wpdb->get_var($sql);
    // now that we have the latest enrollment entry, we can update the table
    $data = array('has_persistence_frm' => 'Yes');
    $where = array('entry_id' => $_POST['item_meta'][3978], 'created_at' => $max_dt);
    $wpdb->update('wp_mccp_enrollment', $data, $where);
  }
}

add_action('frm_after_update_entry', 'update_mccp_persistence', 20, 2);
function update_mccp_persistence($entry_id, $form_id){
  if($form_id == 77){ //change 4 to the form id of the form to copy
    global $wpdb;
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM wp_mccp_persistence WHERE entry_id = %s AND post_type != 'post'", $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'persistence_classification' => $_POST['item_meta'][4243], 'persistence_term' => $_POST['item_meta'][4236], 'term' => $_POST['item_meta'][3981], 'academic_year' => $_POST['item_meta'][4237], 'persistence_date' => $_POST['item_meta'][3980], 'persistence_status' => $_POST['item_meta'][4238], 'persistence_gpa' => $_POST['item_meta'][3987], 'persistence_cumulative_gpa' => $_POST['item_meta'][3988], 'transferred_where' => $_POST['item_meta'][4183], 'explanation_of_status' => $_POST['item_meta'][3984], 'persistence_major' => $_POST['item_meta'][4239], 'was_aew_offered' => $_POST['item_meta'][4180], 'did_student_take_aew' => $_POST['item_meta'][4181], 'courses' => $_POST['item_meta'][4240], 'comments' => $_POST['item_meta'][4179], 'parent_post_id' => $_POST['item_meta'][3978], 'user_id' => $_POST['item_meta'][3990], 'updated_by' => $_POST['item_meta'][4184], 'date_frm_created' => $_POST['item_meta'][4260], 'time_frm_created' => $_POST['item_meta'][4261], 'ip_address' => $_POST['item_meta'][4262], 'ptitle' => $_POST['item_meta'][4241], 'student_key' => $_POST['item_meta'][4185], 'post_type' => $post_type);
    $wpdb->insert('wp_mccp_persistence', $values);
  }
}

add_filter('frm_validate_field_entry', 'check_mccp_birth_date', 10, 3);
function check_mccp_birth_date($errors, $posted_field, $posted_value){
  if($posted_field->id == 3676){ // the ID of the date field to validate
    //check the $posted_value here
    if(strtotime("-10 years") < strtotime($posted_value)){ //if birthday is less than 10 years ago
       //if it doesn't match up, add an error:
       $errors['field'. $posted_field->id] = 'Are you really under 10 years old?';
    }
 }
  return $errors;
}