<?php

add_action('frm_after_create_entry', 'create_k12_enrollment', 20, 2);
function create_k12_enrollment($entry_id, $form_id){
  if($form_id == 69){
    global $wpdb;
    $k12_enrollment_db = $wpdb->prefix.'k12_enrollment';
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mesa_center' => $_POST['item_meta'][4046], 'school_level' => $_POST['item_meta'][3419], 'frst_yr_in_mesa' => $_POST['item_meta'][4292], 'number_of_mesa_years' => $_POST['item_meta'][3421], 'program_participation' => $_POST['item_meta'][4047], 'program_participation_other' => $_POST['item_meta'][4296], 'school_name' => $_POST['item_meta'][3423], 'district_id' => $_POST['item_meta'][3424], 'cur_grade_level' => $_POST['item_meta'][3425], 'teacher' => $_POST['item_meta'][3426], 'gpa' => $_POST['item_meta'][3427], 'ssn' => $_POST['item_meta'][3428], 'dob' => $_POST['item_meta'][3495], 'student_id' => $_POST['item_meta'][3441], 'first_name' => $_POST['item_meta'][3430], 'middle_initial' => $_POST['item_meta'][3431], 'last_name' => $_POST['item_meta'][3429], 'phone' => $_POST['item_meta'][3432], 'email' => $_POST['item_meta'][3433], 'social_media' => $_POST['item_meta'][4048], 'street_address' => $_POST['item_meta'][3435], 'city' => $_POST['item_meta'][4028], 'state' => $_POST['item_meta'][4027], 'zip' => $_POST['item_meta'][3438], 'country' => $_POST['item_meta'][4050], 'cur_math' => $_POST['item_meta'][4053], 'cur_sci' => $_POST['item_meta'][4054], 'cur_eng' => $_POST['item_meta'][4055], 'enrolled_ap_math' => $_POST['item_meta'][3449], 'enrolled_ap_science' => $_POST['item_meta'][3450], 'enrolled_ap_english' => $_POST['item_meta'][3451], 'taken_the_psat' => $_POST['item_meta'][3445], 'taken_the_act' => $_POST['item_meta'][3446], 'taken_the_sat' => $_POST['item_meta'][3447], 'sat_grade' => $_POST['item_meta'][4293], 'gender' => $_POST['item_meta'][4057], 'ethnicity' => $_POST['item_meta'][4058], 'race' => $_POST['item_meta'][4059], 'tribal_affiliation' => $_POST['item_meta'][4010], 'multiracial' => $_POST['item_meta'][4009], 'pri_first_name' => $_POST['item_meta'][3454], 'pri_middle_initial' => $_POST['item_meta'][3456], 'pri_last_name' => $_POST['item_meta'][3455], 'pri_address' => $_POST['item_meta'][3458], 'pri_city' => $_POST['item_meta'][3459], 'pri_state' => $_POST['item_meta'][3460], 'pri_zip' => $_POST['item_meta'][3461], 'pri_country' => $_POST['item_meta'][4051], 'pri_phone' => $_POST['item_meta'][3462], 'pri_email' => $_POST['item_meta'][3463], 'pri_college_graduate' => $_POST['item_meta'][3464], 'pri_employer' => $_POST['item_meta'][3465], 'pri_occupation' => $_POST['item_meta'][3466], 'sec_first_name' => $_POST['item_meta'][3467], 'sec_middle_initial' => $_POST['item_meta'][3468], 'sec_last_name' => $_POST['item_meta'][3469], 'sec_address' => $_POST['item_meta'][3471], 'sec_city' => $_POST['item_meta'][3472], 'sec_state' => $_POST['item_meta'][3473], 'sec_zip' => $_POST['item_meta'][3474], 'sec_country' => $_POST['item_meta'][4052], 'sec_phone' => $_POST['item_meta'][3475], 'sec_email' => $_POST['item_meta'][3476], 'sec_college_graduate' => $_POST['item_meta'][3477], 'sec_employer' => $_POST['item_meta'][3478], 'sec_occupation' => $_POST['item_meta'][3479], 'college' => $_POST['item_meta'][3480], 'other_college' => $_POST['item_meta'][3481], 'career_choices' => $_POST['item_meta'][4060], 'other_career_choice' => $_POST['item_meta'][4300], 'household_size' => $_POST['item_meta'][3488], 'lang_other_than_eng' => $_POST['item_meta'][3483], 'what_language' => $_POST['item_meta'][3484], 'has_computer_access' => $_POST['item_meta'][3485], 'has_internet_access' => $_POST['item_meta'][3486], 'lunch_eligible' => $_POST['item_meta'][3487], 'student_photo_consent' => $_POST['item_meta'][3490], 'comments' => $_POST['item_meta'][3491], 'permission' => $_POST['item_meta'][4061], 'user_id' => $_POST['item_meta'][3417], 'updated_by' => $_POST['item_meta'][4032], 'parent_post_id' => $entry_id, 'ptitle' => $_POST['item_meta'][4031], 'student_key' => $_POST['item_meta'][4120], 'date_frm_created' => $_POST['item_meta'][4255], 'time_frm_created' => $_POST['item_meta'][4256], 'ip_address' => $_POST['item_meta'][4254], 'post_type' => 'post', 'has_persistence_frm' => $_POST['item_meta'][4328], 'has_activities_frm' => $_POST['item_meta'][4326], 'has_exit_frm' => $_POST['item_meta'][4327]);
    $wpdb->insert($k12_enrollment_db, $values);
  }
}

add_action('frm_after_update_entry', 'update_k12_enrollment', 20, 2);
function update_k12_enrollment($entry_id, $form_id){
  if($form_id == 69){ //change 4 to the form id of the form to copy
    global $wpdb;
    $k12_enrollment_db = $wpdb->prefix.'k12_enrollment';
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM %s WHERE entry_id = %s AND post_type != 'post'", $k12_enrollment_db, $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mesa_center' => $_POST['item_meta'][4046], 'school_level' => $_POST['item_meta'][3419], 'frst_yr_in_mesa' => $_POST['item_meta'][4292], 'number_of_mesa_years' => $_POST['item_meta'][3421], 'program_participation' => $_POST['item_meta'][4047], 'program_participation_other' => $_POST['item_meta'][4296], 'school_name' => $_POST['item_meta'][3423], 'district_id' => $_POST['item_meta'][3424], 'cur_grade_level' => $_POST['item_meta'][3425], 'teacher' => $_POST['item_meta'][3426], 'gpa' => $_POST['item_meta'][3427], 'ssn' => $_POST['item_meta'][3428], 'dob' => $_POST['item_meta'][3495], 'student_id' => $_POST['item_meta'][3441], 'first_name' => $_POST['item_meta'][3430], 'middle_initial' => $_POST['item_meta'][3431], 'last_name' => $_POST['item_meta'][3429], 'phone' => $_POST['item_meta'][3432], 'email' => $_POST['item_meta'][3433], 'social_media' => $_POST['item_meta'][4048], 'street_address' => $_POST['item_meta'][3435], 'city' => $_POST['item_meta'][4028], 'state' => $_POST['item_meta'][4027], 'zip' => $_POST['item_meta'][3438], 'country' => $_POST['item_meta'][4050], 'cur_math' => $_POST['item_meta'][4053], 'cur_sci' => $_POST['item_meta'][4054], 'cur_eng' => $_POST['item_meta'][4055], 'enrolled_ap_math' => $_POST['item_meta'][3449], 'enrolled_ap_science' => $_POST['item_meta'][3450], 'enrolled_ap_english' => $_POST['item_meta'][3451], 'taken_the_psat' => $_POST['item_meta'][3445], 'taken_the_act' => $_POST['item_meta'][3446], 'taken_the_sat' => $_POST['item_meta'][3447], 'sat_grade' => $_POST['item_meta'][4293], 'gender' => $_POST['item_meta'][4057], 'ethnicity' => $_POST['item_meta'][4058], 'race' => $_POST['item_meta'][4059], 'tribal_affiliation' => $_POST['item_meta'][4010], 'multiracial' => $_POST['item_meta'][4009], 'pri_first_name' => $_POST['item_meta'][3454], 'pri_middle_initial' => $_POST['item_meta'][3456], 'pri_last_name' => $_POST['item_meta'][3455], 'pri_address' => $_POST['item_meta'][3458], 'pri_city' => $_POST['item_meta'][3459], 'pri_state' => $_POST['item_meta'][3460], 'pri_zip' => $_POST['item_meta'][3461], 'pri_country' => $_POST['item_meta'][4051], 'pri_phone' => $_POST['item_meta'][3462], 'pri_email' => $_POST['item_meta'][3463], 'pri_college_graduate' => $_POST['item_meta'][3464], 'pri_employer' => $_POST['item_meta'][3465], 'pri_occupation' => $_POST['item_meta'][3466], 'sec_first_name' => $_POST['item_meta'][3467], 'sec_middle_initial' => $_POST['item_meta'][3468], 'sec_last_name' => $_POST['item_meta'][3469], 'sec_address' => $_POST['item_meta'][3471], 'sec_city' => $_POST['item_meta'][3472], 'sec_state' => $_POST['item_meta'][3473], 'sec_zip' => $_POST['item_meta'][3474], 'sec_country' => $_POST['item_meta'][4052], 'sec_phone' => $_POST['item_meta'][3475], 'sec_email' => $_POST['item_meta'][3476], 'sec_college_graduate' => $_POST['item_meta'][3477], 'sec_employer' => $_POST['item_meta'][3478], 'sec_occupation' => $_POST['item_meta'][3479], 'college' => $_POST['item_meta'][3480], 'other_college' => $_POST['item_meta'][3481], 'career_choices' => $_POST['item_meta'][4060], 'other_career_choice' => $_POST['item_meta'][4300], 'household_size' => $_POST['item_meta'][3488], 'lang_other_than_eng' => $_POST['item_meta'][3483], 'what_language' => $_POST['item_meta'][3484], 'has_computer_access' => $_POST['item_meta'][3485], 'has_internet_access' => $_POST['item_meta'][3486], 'lunch_eligible' => $_POST['item_meta'][3487], 'student_photo_consent' => $_POST['item_meta'][3490], 'comments' => $_POST['item_meta'][3491], 'permission' => $_POST['item_meta'][4061], 'user_id' => $_POST['item_meta'][3417], 'updated_by' => $_POST['item_meta'][4032], 'parent_post_id' => $_POST['item_meta'][4316], 'ptitle' => $_POST['item_meta'][4031], 'student_key' => $_POST['item_meta'][4120], 'date_frm_created' => $_POST['item_meta'][4255], 'time_frm_created' => $_POST['item_meta'][4256], 'ip_address' => $_POST['item_meta'][4254], 'post_type' => $post_type, 'has_persistence_frm' => $_POST['item_meta'][4328], 'has_activities_frm' => $_POST['item_meta'][4326], 'has_exit_frm' => $_POST['item_meta'][4327]);
    $wpdb->insert($k12_enrollment_db, $values);
  }
}

add_action('frm_after_create_entry', 'create_k12_teacherprofile', 20, 2);
function create_k12_teacherprofile($entry_id, $form_id){
  if($form_id == 72){ //change 4 to the form id of the form to copy
    global $wpdb;
    $k12_teacherprofile_db = $wpdb->prefix . 'k12_teacherprofile';
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mesa_center' => $_POST['item_meta'][4196], 'first_name' => $_POST['item_meta'][3582], 'middle_initial' => $_POST['item_meta'][3583], 'last_name' => $_POST['item_meta'][3584], 'home_address' => $_POST['item_meta'][3586], 'city' => $_POST['item_meta'][3587], 'state' => $_POST['item_meta'][4098], 'zip' => $_POST['item_meta'][3589], 'daytime_phone' => $_POST['item_meta'][3590], 'evening_phone' => $_POST['item_meta'][3591], 'email_address' => $_POST['item_meta'][3592], 'allow_call_at_home' => $_POST['item_meta'][3593], 'best_time_to_call' => $_POST['item_meta'][4097], 'school_name' => $_POST['item_meta'][3595], 'school_address' => $_POST['item_meta'][3596], 'school_city' => $_POST['item_meta'][3597], 'school_state' => $_POST['item_meta'][4099], 'school_zip' => $_POST['item_meta'][3599], 'school_phone_number' => $_POST['item_meta'][3600], 'school_fax' => $_POST['item_meta'][3601], 'school_principal' => $_POST['item_meta'][3602], 'district_name' => $_POST['item_meta'][3603], 'district_address' => $_POST['item_meta'][3604], 'district_city' => $_POST['item_meta'][3605], 'district_state' => $_POST['item_meta'][4100], 'district_zip' => $_POST['item_meta'][3639], 'district_phone_number' => $_POST['item_meta'][3607], 'district_superintendent' => $_POST['item_meta'][3608], 'emer_cntct_name' => $_POST['item_meta'][3609], 'emer_cntct_daytime_phone' => $_POST['item_meta'][3610], 'emer_cntct_evening_phone' => $_POST['item_meta'][3611], 'emer_cntct_relationship' => $_POST['item_meta'][3613], 'emer_cntct_address' => $_POST['item_meta'][3612], 'emer_cntct_city' => $_POST['item_meta'][4022], 'emer_cntct_state' => $_POST['item_meta'][4101], 'emer_cntct_zip' => $_POST['item_meta'][4024], 'college_major' => $_POST['item_meta'][3614], 'college_minor' => $_POST['item_meta'][3615], 'college_degrees' => $_POST['item_meta'][4102], 'grad_yr' => $_POST['item_meta'][3617], 'certification' => $_POST['item_meta'][3618], 'when_became_mesa_advisor' => $_POST['item_meta'][3619], 'year_began_teaching' => $_POST['item_meta'][3620], 'how_many_prof_dev_evnts' => $_POST['item_meta'][3621], 'receive_stipend_support' => $_POST['item_meta'][4091], 'receive_release_time' => $_POST['item_meta'][3623], 'when_is_your_prep_period' => $_POST['item_meta'][3624], 'el_subjects' => $_POST['item_meta'][4105], 'elem_math_lvls' => $_POST['item_meta'][3628], 'elem_sci_lvls' => $_POST['item_meta'][3631], 'jh_subjects' => $_POST['item_meta'][4106], 'jh_ms_math_lvls' => $_POST['item_meta'][3630], 'ap_classes' => $_POST['item_meta'][4089], 'hs_subjects' => $_POST['item_meta'][4107], 'hs_math_lvls' => $_POST['item_meta'][4088], 'hs_sci_lvls' => $_POST['item_meta'][4090], 'other_skills' => $_POST['item_meta'][3632], 'interest_in_presenting' => $_POST['item_meta'][3634], 'attend_mesa_training' => $_POST['item_meta'][3633], 'wants_to_present' => $_POST['item_meta'][4109], 'wants_training' => $_POST['item_meta'][4108], 'user_id' => $_POST['item_meta'][3638], 'updated_by' => $_POST['item_meta'][4096], 'parent_post_id' => $entry_id, 'date_frm_created' => $_POST['item_meta'][4275], 'time_frm_created' => $_POST['item_meta'][4276], 'ip_address' => $_POST['item_meta'][4277], 'ptitle' => $_POST['item_meta'][4110], 'post_type' => 'post');
    $wpdb->insert($k12_teacherprofile_db, $values);
  }
}

add_action('frm_after_update_entry', 'update_k12_teacherprofile', 20, 2);
function update_k12_teacherprofile($entry_id, $form_id){
  if($form_id == 72){ //change 4 to the form id of the form to copy
    global $wpdb;
    $k12_teacherprofile_db = $wpdb->prefix . 'k12_teacherprofile';
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM %s WHERE entry_id = %s AND post_type != 'post'", $k12_teacherprofile_db, $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'mesa_center' => $_POST['item_meta'][4196], 'first_name' => $_POST['item_meta'][3582], 'middle_initial' => $_POST['item_meta'][3583], 'last_name' => $_POST['item_meta'][3584], 'home_address' => $_POST['item_meta'][3586], 'city' => $_POST['item_meta'][3587], 'state' => $_POST['item_meta'][4098], 'zip' => $_POST['item_meta'][3589], 'daytime_phone' => $_POST['item_meta'][3590], 'evening_phone' => $_POST['item_meta'][3591], 'email_address' => $_POST['item_meta'][3592], 'allow_call_at_home' => $_POST['item_meta'][3593], 'best_time_to_call' => $_POST['item_meta'][4097], 'school_name' => $_POST['item_meta'][3595], 'school_address' => $_POST['item_meta'][3596], 'school_city' => $_POST['item_meta'][3597], 'school_state' => $_POST['item_meta'][4099], 'school_zip' => $_POST['item_meta'][3599], 'school_phone_number' => $_POST['item_meta'][3600], 'school_fax' => $_POST['item_meta'][3601], 'school_principal' => $_POST['item_meta'][3602], 'district_name' => $_POST['item_meta'][3603], 'district_address' => $_POST['item_meta'][3604], 'district_city' => $_POST['item_meta'][3605], 'district_state' => $_POST['item_meta'][4100], 'district_zip' => $_POST['item_meta'][3639], 'district_phone_number' => $_POST['item_meta'][3607], 'district_superintendent' => $_POST['item_meta'][3608], 'emer_cntct_name' => $_POST['item_meta'][3609], 'emer_cntct_daytime_phone' => $_POST['item_meta'][3610], 'emer_cntct_evening_phone' => $_POST['item_meta'][3611], 'emer_cntct_relationship' => $_POST['item_meta'][3613], 'emer_cntct_address' => $_POST['item_meta'][3612], 'emer_cntct_city' => $_POST['item_meta'][4022], 'emer_cntct_state' => $_POST['item_meta'][4101], 'emer_cntct_zip' => $_POST['item_meta'][4024], 'college_major' => $_POST['item_meta'][3614], 'college_minor' => $_POST['item_meta'][3615], 'college_degrees' => $_POST['item_meta'][4102], 'grad_yr' => $_POST['item_meta'][3617], 'certification' => $_POST['item_meta'][3618], 'when_became_mesa_advisor' => $_POST['item_meta'][3619], 'year_began_teaching' => $_POST['item_meta'][3620], 'how_many_prof_dev_evnts' => $_POST['item_meta'][3621], 'receive_stipend_support' => $_POST['item_meta'][4091], 'receive_release_time' => $_POST['item_meta'][3623], 'when_is_your_prep_period' => $_POST['item_meta'][3624], 'el_subjects' => $_POST['item_meta'][4105], 'elem_math_lvls' => $_POST['item_meta'][3628], 'elem_sci_lvls' => $_POST['item_meta'][3631], 'jh_subjects' => $_POST['item_meta'][4106], 'jh_ms_math_lvls' => $_POST['item_meta'][3630], 'ap_classes' => $_POST['item_meta'][4089], 'hs_subjects' => $_POST['item_meta'][4107], 'hs_math_lvls' => $_POST['item_meta'][4088], 'hs_sci_lvls' => $_POST['item_meta'][4090], 'other_skills' => $_POST['item_meta'][3632], 'interest_in_presenting' => $_POST['item_meta'][3634], 'attend_mesa_training' => $_POST['item_meta'][3633], 'wants_to_present' => $_POST['item_meta'][4109], 'wants_training' => $_POST['item_meta'][4108], 'user_id' => $_POST['item_meta'][3638], 'updated_by' => $_POST['item_meta'][4096], 'parent_post_id' => $_POST['item_meta'][4355], 'date_frm_created' => $_POST['item_meta'][4275], 'time_frm_created' => $_POST['item_meta'][4276], 'ip_address' => $_POST['item_meta'][4277], 'ptitle' => $_POST['item_meta'][4110], 'post_type' => $post_type);
    $wpdb->insert($k12_teacherprofile_db, $values);
  }
}


add_action('frm_after_create_entry', 'create_k12_exit', 20, 2);
function create_k12_exit($entry_id, $form_id){
  if($form_id == 71){ //change 4 to the form id of the form to copy
    global $wpdb, $frmdb;
    $k12_enrollment_db = $wpdb->prefix.'k12_enrollment';
    $k12_exit_db = $wpdb->prefix . 'k12_exit';
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'first_name' => $_POST['item_meta'][3506], 'middle_initial' => $_POST['item_meta'][3507], 'last_name' => $_POST['item_meta'][3508], 'perm_address' => $_POST['item_meta'][3509], 'perm_city' => $_POST['item_meta'][3510], 'perm_state' => $_POST['item_meta'][3511], 'perm_zip' => $_POST['item_meta'][3512], 'perm_phone' => $_POST['item_meta'][3513], 'email' => $_POST['item_meta'][3514], 'dob' => $_POST['item_meta'][3515], 'school' => $_POST['item_meta'][3516], 'intended_grad_dt' => $_POST['item_meta'][3517], 'gender' => $_POST['item_meta'][3519], 'ethnicity' => $_POST['item_meta'][4125], 'race' => $_POST['item_meta'][4126], 'tribal_affiliation' => $_POST['item_meta'][4005], 'multiracial' => $_POST['item_meta'][3520], 'mesa_center' => $_POST['item_meta'][4131], 'mesa_first_yr' => $_POST['item_meta'][4129], 'grade_lvl_started_mesa' => $_POST['item_meta'][3525], 'personal_growth_experience' => $_POST['item_meta'][3526], 'mesa_services' => $_POST['item_meta'][4132], 'other_services' => $_POST['item_meta'][3528], 'satisfaction_tutoring' => $_POST['item_meta'][4201], 'satisfaction_academic_advising' => $_POST['item_meta'][4202], 'satisfaction_career_advising' => $_POST['item_meta'][4203], 'satisfaction_workshops' => $_POST['item_meta'][4204], 'satisfaction_mesa_classes' => $_POST['item_meta'][4205], 'satisfaction_prof_dev_ops' => $_POST['item_meta'][4206], 'satisfaction_enrollment_info' => $_POST['item_meta'][4207], 'satisfaction_scholarship_info' => $_POST['item_meta'][4208], 'satisfaction_internship_info' => $_POST['item_meta'][4209], 'satisfaction_day_competitions' => $_POST['item_meta'][4210], 'satisfaction_comments' => $_POST['item_meta'][3539], 'meetings_tutoring' => $_POST['item_meta'][4133], 'meetings_counseling' => $_POST['item_meta'][4134], 'meetings_career_advising' => $_POST['item_meta'][4135], 'meetings_prof_dev_ops' => $_POST['item_meta'][4136], 'meetings_enrollment_info' => $_POST['item_meta'][4137], 'meetings_scholarship_info' => $_POST['item_meta'][4138], 'meetings_internship_ops' => $_POST['item_meta'][4139], 'meetings_acad_plan_chng' => $_POST['item_meta'][4140], 'meetings_registration_difficulties' => $_POST['item_meta'][4141], 'meetings_choosing_courses' => $_POST['item_meta'][4142], 'meetings_course_work_help' => $_POST['item_meta'][4143], 'meetings_transfer_assistance' => $_POST['item_meta'][4144], 'meetings_did_not_meet' => $_POST['item_meta'][4145], 'meetings_comments' => $_POST['item_meta'][3553], 'have_academic_plan' => $_POST['item_meta'][3554], 'academic_plan_helpful' => $_POST['item_meta'][3555], 'college_entrance_exams' => $_POST['item_meta'][4146], 'plan_after_high_school' => $_POST['item_meta'][3557], 'reason_not_to_attend_college' => $_POST['item_meta'][3558], 'completed_science_classes' => $_POST['item_meta'][4148], 'completed_math_classes' => $_POST['item_meta'][4147], 'other_college_prep_courses' => $_POST['item_meta'][3561], 'highest_lvl_education_planned' => $_POST['item_meta'][3562], 'applied_for_admission' => $_POST['item_meta'][3563], 'which_colleges_applied_to' => $_POST['item_meta'][3564], 'accepted_to_college' => $_POST['item_meta'][3565], 'which_colleges_acepted' => $_POST['item_meta'][3566], 'intended_college' => $_POST['item_meta'][3567], 'first_choice_major' => $_POST['item_meta'][4151], 'second_choice_major' => $_POST['item_meta'][4152], 'received_any_scholarships' => $_POST['item_meta'][3570], 'scholarship_sponsors' => $_POST['item_meta'][3571], 'highest_lvl_educ_mother' => $_POST['item_meta'][3572], 'highest_lvl_educ_father' => $_POST['item_meta'][3573], 'challenge_paying_for_college' => $_POST['item_meta'][3574], 'challenge_financial_aid_process' => $_POST['item_meta'][3575], 'challenge_living_away_from_home' => $_POST['item_meta'][3576], 'challenge_parents_desire' => $_POST['item_meta'][3577], 'other_challenges' => $_POST['item_meta'][3578], 'program_improvement_suggestions' => $_POST['item_meta'][3579], 'parent_post_id' => $_POST['item_meta'][3505], 'user_id' => $_POST['item_meta'][3581], 'updated_by' => $_POST['item_meta'][4117], 'date_frm_created' => $_POST['item_meta'][4269], 'time_frm_created' => $_POST['item_meta'][4270], 'ip_address' => $_POST['item_meta'][4271], 'student_key' => $_POST['item_meta'][4127], 'ptitle' => $_POST['item_meta'][4119], 'post_type' => 'post');
    $wpdb->insert($k12_exit_db, $values);

    // update has_exit_frm in parent record
    $data = array('meta_value' => 'Yes');
    $where = array('item_id' => $_POST['item_meta'][3505], 'field_id' => '4327');
    $wpdb->update($frmdb->entry_metas, $data, $where);

    // update latest enrollment entry so has_exit_frm is yes
    // first get the latest created_at date
    $sql = $wpdb->prepare("SELECT max(created_at) FROM %s WHERE entry_id = %s", $k12_enrollment_db, $_POST['item_meta'][3505]);
    $max_dt = $wpdb->get_var($sql);
    // now that we have the latest enrollment entry, we can update the table
    $data = array('has_exit_frm' => 'Yes');
    $where = array('entry_id' => $_POST['item_meta'][3505], 'created_at' => $max_dt);
    $wpdb->update($k12_enrollment_db, $data, $where);
  }
}

add_action('frm_after_update_entry', 'update_k12_exit', 20, 2);
function update_k12_exit($entry_id, $form_id){
  if($form_id == 71){ //change 4 to the form id of the form to copy
    global $wpdb;
    $k12_exit_db = $wpdb->prefix . 'k12_exit';
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM %s WHERE entry_id = %s AND post_type != 'post'", $k12_exit_db, $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'first_name' => $_POST['item_meta'][3506], 'middle_initial' => $_POST['item_meta'][3507], 'last_name' => $_POST['item_meta'][3508], 'perm_address' => $_POST['item_meta'][3509], 'perm_city' => $_POST['item_meta'][3510], 'perm_state' => $_POST['item_meta'][3511], 'perm_zip' => $_POST['item_meta'][3512], 'perm_phone' => $_POST['item_meta'][3513], 'email' => $_POST['item_meta'][3514], 'dob' => $_POST['item_meta'][3515], 'school' => $_POST['item_meta'][3516], 'intended_grad_dt' => $_POST['item_meta'][3517], 'gender' => $_POST['item_meta'][3519], 'ethnicity' => $_POST['item_meta'][4125], 'race' => $_POST['item_meta'][4126], 'tribal_affiliation' => $_POST['item_meta'][4005], 'multiracial' => $_POST['item_meta'][3520], 'mesa_center' => $_POST['item_meta'][4131], 'mesa_first_yr' => $_POST['item_meta'][4129], 'grade_lvl_started_mesa' => $_POST['item_meta'][3525], 'personal_growth_experience' => $_POST['item_meta'][3526], 'mesa_services' => $_POST['item_meta'][4132], 'other_services' => $_POST['item_meta'][3528], 'satisfaction_tutoring' => $_POST['item_meta'][4201], 'satisfaction_academic_advising' => $_POST['item_meta'][4202], 'satisfaction_career_advising' => $_POST['item_meta'][4203], 'satisfaction_workshops' => $_POST['item_meta'][4204], 'satisfaction_mesa_classes' => $_POST['item_meta'][4205], 'satisfaction_prof_dev_ops' => $_POST['item_meta'][4206], 'satisfaction_enrollment_info' => $_POST['item_meta'][4207], 'satisfaction_scholarship_info' => $_POST['item_meta'][4208], 'satisfaction_internship_info' => $_POST['item_meta'][4209], 'satisfaction_day_competitions' => $_POST['item_meta'][4210], 'satisfaction_comments' => $_POST['item_meta'][3539], 'meetings_tutoring' => $_POST['item_meta'][4133], 'meetings_counseling' => $_POST['item_meta'][4134], 'meetings_career_advising' => $_POST['item_meta'][4135], 'meetings_prof_dev_ops' => $_POST['item_meta'][4136], 'meetings_enrollment_info' => $_POST['item_meta'][4137], 'meetings_scholarship_info' => $_POST['item_meta'][4138], 'meetings_internship_ops' => $_POST['item_meta'][4139], 'meetings_acad_plan_chng' => $_POST['item_meta'][4140], 'meetings_registration_difficulties' => $_POST['item_meta'][4141], 'meetings_choosing_courses' => $_POST['item_meta'][4142], 'meetings_course_work_help' => $_POST['item_meta'][4143], 'meetings_transfer_assistance' => $_POST['item_meta'][4144], 'meetings_did_not_meet' => $_POST['item_meta'][4145], 'meetings_comments' => $_POST['item_meta'][3553], 'have_academic_plan' => $_POST['item_meta'][3554], 'academic_plan_helpful' => $_POST['item_meta'][3555], 'college_entrance_exams' => $_POST['item_meta'][4146], 'plan_after_high_school' => $_POST['item_meta'][3557], 'reason_not_to_attend_college' => $_POST['item_meta'][3558], 'completed_science_classes' => $_POST['item_meta'][4148], 'completed_math_classes' => $_POST['item_meta'][4147], 'other_college_prep_courses' => $_POST['item_meta'][3561], 'highest_lvl_education_planned' => $_POST['item_meta'][3562], 'applied_for_admission' => $_POST['item_meta'][3563], 'which_colleges_applied_to' => $_POST['item_meta'][3564], 'accepted_to_college' => $_POST['item_meta'][3565], 'which_colleges_acepted' => $_POST['item_meta'][3566], 'intended_college' => $_POST['item_meta'][3567], 'first_choice_major' => $_POST['item_meta'][4151], 'second_choice_major' => $_POST['item_meta'][4152], 'received_any_scholarships' => $_POST['item_meta'][3570], 'scholarship_sponsors' => $_POST['item_meta'][3571], 'highest_lvl_educ_mother' => $_POST['item_meta'][3572], 'highest_lvl_educ_father' => $_POST['item_meta'][3573], 'challenge_paying_for_college' => $_POST['item_meta'][3574], 'challenge_financial_aid_process' => $_POST['item_meta'][3575], 'challenge_living_away_from_home' => $_POST['item_meta'][3576], 'challenge_parents_desire' => $_POST['item_meta'][3577], 'other_challenges' => $_POST['item_meta'][3578], 'program_improvement_suggestions' => $_POST['item_meta'][3579], 'parent_post_id' => $_POST['item_meta'][3505], 'user_id' => $_POST['item_meta'][3581], 'updated_by' => $_POST['item_meta'][4117], 'date_frm_created' => $_POST['item_meta'][4269], 'time_frm_created' => $_POST['item_meta'][4270], 'ip_address' => $_POST['item_meta'][4271], 'student_key' => $_POST['item_meta'][4127], 'ptitle' => $_POST['item_meta'][4119], 'post_type' => $post_type);
    $wpdb->insert($k12_exit_db, $values);
  }
}

add_action('frm_after_create_entry', 'create_k12_activities', 20, 2);
function create_k12_activities($entry_id, $form_id){
  if($form_id == 70){ //change 4 to the form id of the form to copy
    global $wpdb, $frmdb;
    $k12_enrollment_db = $wpdb->prefix.'k12_enrollment';
    $k12_activities_db = $wpdb->prefix . 'k12_activities';
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'student_activities' => $_POST['item_meta'][4113], 'internships' => $_POST['item_meta'][4112], 'scholarship_awards' => $_POST['item_meta'][4114], 'user_id' => $_POST['item_meta'][3504], 'parent_post_id' => $_POST['item_meta'][3499], 'updated_by' => $_POST['item_meta'][4111], 'date_frm_created' => $_POST['item_meta'][4263], 'time_frm_created' => $_POST['item_meta'][4264], 'ip_address' => $_POST['item_meta'][4265], 'student_key' => $_POST['item_meta'][4116], 'ptitle' => $_POST['item_meta'][4115], 'post_type' => 'post');
    $wpdb->insert($k12_activities_db, $values);

    // update parent form has_activities_frm value to yes
    $data = array('meta_value' => 'Yes');
    $where = array('item_id' => $_POST['item_meta'][3499], 'field_id' => '4326');
    $wpdb->update($frmdb->entry_metas, $data, $where);

    // update latest enrollment entry so has_activities_frm is yes
    // first get the latest created_at date
    $sql = $wpdb->prepare("SELECT max(created_at) FROM %s WHERE entry_id = %s", $k12_enrollment_db, $_POST['item_meta'][3499]);
    $max_dt = $wpdb->get_var($sql);
    // now that we have the latest enrollment entry, we can update the table
    $data = array('has_activities_frm' => 'Yes');
    $where = array('entry_id' => $_POST['item_meta'][3499], 'created_at' => $max_dt);
    $wpdb->update($k12_enrollment_db, $data, $where);
  }
}

add_action('frm_after_update_entry', 'update_k12_activities', 20, 2);
function update_k12_activities($entry_id, $form_id){
  if($form_id == 70){ //change 4 to the form id of the form to copy
    global $wpdb;
    $k12_activities_db = $wpdb->prefix . 'k12_activities';
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM %s WHERE entry_id = %s AND post_type != 'post'", $k12_activities_db, $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'student_activities' => $_POST['item_meta'][4113], 'internships' => $_POST['item_meta'][4112], 'scholarship_awards' => $_POST['item_meta'][4114], 'user_id' => $_POST['item_meta'][3504], 'parent_post_id' => $_POST['item_meta'][3499], 'updated_by' => $_POST['item_meta'][4111], 'date_frm_created' => $_POST['item_meta'][4263], 'time_frm_created' => $_POST['item_meta'][4264], 'ip_address' => $_POST['item_meta'][4265], 'student_key' => $_POST['item_meta'][4116], 'ptitle' => $_POST['item_meta'][4115], 'post_type' => $post_type);
    $wpdb->insert($k12_activities_db, $values);
  }
}

add_action('frm_after_create_entry', 'create_k12_persistence', 20, 2);
function create_k12_persistence($entry_id, $form_id){
  if($form_id == 68){ //change 4 to the form id of the form to copy
    global $wpdb, $frmdb;
    $k12_enrollment_db = $wpdb->prefix.'k12_enrollment';
    $k12_persistence_db = $wpdb->prefix . 'k12_persistence';
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'persistence_term' => $_POST['item_meta'][3412], 'term' => $_POST['item_meta'][3401], 'school_year' => $_POST['item_meta'][3413], 'persistence_date' => $_POST['item_meta'][3403], 'persistence_status' => $_POST['item_meta'][3414], 'persistence_gpa' => $_POST['item_meta'][3405], 'explanation_of_status' => $_POST['item_meta'][3406], 'persistence_major' => $_POST['item_meta'][3415], 'courses' => $_POST['item_meta'][3416], 'parent_post_id' => $_POST['item_meta'][3399], 'user_id' => $_POST['item_meta'][3409], 'updated_by' => $_POST['item_meta'][3411], 'date_frm_created' => $_POST['item_meta'][4257], 'time_frm_created' => $_POST['item_meta'][4259], 'ip_address' => $_POST['item_meta'][4258], 'student_key' => $_POST['item_meta'][4124], 'ptitle' => $_POST['item_meta'][3410], 'post_type' => 'post');
    $wpdb->insert($k12_persistence_db, $values);

    // update has_persistence_frm in parent record
    $data = array('meta_value' => 'Yes');
    $where = array('item_id' => $_POST['item_meta'][3399], 'field_id' => '4328');
    $wpdb->update($frmdb->entry_metas, $data, $where);

    // update latest enrollment entry so has_persistence_frm is yes
    // first get the latest created_at date
    $sql = $wpdb->prepare("SELECT max(created_at) FROM %s WHERE entry_id = %s", $k12_enrollment_db, $_POST['item_meta'][3399]);
    $max_dt = $wpdb->get_var($sql);
    // now that we have the latest enrollment entry, we can update the table
    $data = array('has_persistence_frm' => 'Yes');
    $where = array('entry_id' => $_POST['item_meta'][3399], 'created_at' => $max_dt);
    $wpdb->update($k12_enrollment_db, $data, $where);
  }
}

add_action('frm_after_update_entry', 'update_k12_persistence', 20, 2);
function update_k12_persistence($entry_id, $form_id){
  if($form_id == 68){ //change 4 to the form id of the form to copy
    global $wpdb;
    $k12_persistence_db = $wpdb->prefix . 'k12_persistence';
    //get the number of revisions already in the database
    $sql = $wpdb->prepare("SELECT count(*) FROM %s WHERE entry_id = %s AND post_type != 'post'", $k12_persistence_db, $entry_id);
    $rec_cnt = $wpdb->get_var($sql);
    $num_revisions = ($rec_cnt === null ) ? 1 : (int) $rec_cnt + 1;
    $post_type = $entry_id . "-revision-v" . (string) $num_revisions;
    $values = array('entry_id' => $entry_id, 'form_id' => $form_id, 'persistence_term' => $_POST['item_meta'][3412], 'term' => $_POST['item_meta'][3401], 'school_year' => $_POST['item_meta'][3413], 'persistence_date' => $_POST['item_meta'][3403], 'persistence_status' => $_POST['item_meta'][3414], 'persistence_gpa' => $_POST['item_meta'][3405], 'explanation_of_status' => $_POST['item_meta'][3406], 'persistence_major' => $_POST['item_meta'][3415], 'courses' => $_POST['item_meta'][3416], 'parent_post_id' => $_POST['item_meta'][3399], 'user_id' => $_POST['item_meta'][3409], 'updated_by' => $_POST['item_meta'][3411], 'date_frm_created' => $_POST['item_meta'][4257], 'time_frm_created' => $_POST['item_meta'][4259], 'ip_address' => $_POST['item_meta'][4258], 'student_key' => $_POST['item_meta'][4124], 'ptitle' => $_POST['item_meta'][3410], 'post_type' => $post_type);
    $wpdb->insert($k12_persistence_db, $values);
  }
}

add_filter('frm_validate_field_entry', 'check_k12_birth_date', 10, 3);
function check_k12_birth_date($errors, $posted_field, $posted_value){
  if($posted_field->id == 3495){ // the ID of the date field to validate
    //check the $posted_value here
    if(strtotime("-10 years") < strtotime($posted_value)){ //if birthday is less than 10 years ago
       //if it doesn't match up, add an error:
       $errors['field'. $posted_field->id] = 'Are you really under 10 years old?';
    }
 }
  return $errors;
}
