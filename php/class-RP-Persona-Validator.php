<?php

class Persona_Validator {

    public function validate($form, $options) {
        unset($options['errors']);
        $indi = new RP_Individual_Record();
        $is_update = false;
        if (isset($form['persona_page']) && !empty($form['persona_page'])) {
            $indi->page = (trim(esc_attr($form['persona_page'])));
            $is_update = true;
        }
        if (isset($form['personId']) && !empty($form['personId'])) {
            $indi->id = trim(esc_attr($form['personId']));
            $is_update = true;
        }
        if (isset($form['batchId']) && !empty($form['batchId'])) {
            $indi->batch_id = trim(esc_attr($form['batchId']));
            $is_update = true;
        }

        $name = new RP_Personal_Name();
        $indi->names[] = $name;

        if (isset($form['rp_prefix']) && !empty($form['rp_prefix'])) {
            $name->rp_name->pieces->prefix = trim(esc_attr($form['rp_prefix']));
            $is_update = true;
        }
        if (isset($form['rp_first']) && !empty($form['rp_first'])) {
            $name->rp_name->pieces->given = trim(esc_attr($form['rp_first']));
            $is_update = true;
        }
        if (isset($form['rp_middle']) && !empty($form['rp_middle'])) {
            $name->rp_name->pieces->given .= " " . trim(esc_attr($form['rp_middle']));
            $is_update = true;
        }
        if (isset($form['rp_last']) && !empty($form['rp_last'])) {
            $name->rp_name->pieces->surname = trim(esc_attr($form['rp_last']));
            $is_update = true;
        }
        if (isset($form['rp_suffix']) && !empty($form['rp_suffix'])) {
            $name->rp_name->pieces->suffix = trim(esc_attr($form['rp_suffix']));
            $is_update = true;
        }
        if (isset($form['rp_full']) && !empty($form['rp_full'])) {
            $name->rp_name->full = trim(esc_attr($form['rp_full']));
            $is_update = true;
        }

        if (isset($form['pickgender']) && !empty($form['pickgender'])) {
            $indi->gender = trim(esc_attr($form['pickgender']));
            $is_update = true;
        }

        //if (isset($form['privacy_grp']) && !empty($form['privacy_grp'])) {
        //    $indi->id(trim(esc_attr($form['privacy_grp'])));
        //    $is_update = true;
        //}


        if (isset($form['rp_bio']) && !empty($form['rp_bio'])) {
            $note = new RP_Note();
            $indi->notes[] = $note;
            $note->text = trim(esc_attr($form['rp_bio']));
            $is_update = true;
        }
/*
        if (isset($form['rp_claimtype']) && !empty($form['rp_claimtype'])) {
            $indi->id(trim(esc_attr($form['rp_claimtype'])));
            $is_update = true;
        }
        if (isset($form['rp_claimdate']) && !empty($form['rp_claimdate'])) {
            $indi->id(trim(esc_attr($form['rp_claimdate'])));
            $is_update = true;
        }
        if (isset($form['rp_claimplace']) && !empty($form['rp_claimplace'])) {
            $indi->id(trim(esc_attr($form['rp_claimplace'])));
            $is_update = true;
        }
        if (isset($form['rp_classification']) && !empty($form['rp_classification'])) {
            $indi->id(trim(esc_attr($form['rp_classification'])));
            $is_update = true;
        }
        if (isset($form['img1']) && !empty($form['img1'])) {
            $indi->id(trim(esc_attr($form['img1'])));
            $is_update = true;
        }
        if (isset($form['img2']) && !empty($form['img2'])) {
            $indi->id(trim(esc_attr($form['img2'])));
            $is_update = true;
        }
        if (isset($form['img3']) && !empty($form['img3'])) {
            $indi->id(trim(esc_attr($form['img3'])));
            $is_update = true;
        }
        if (isset($form['img4']) && !empty($form['img4'])) {
            $indi->id(trim(esc_attr($form['img4'])));
            $is_update = true;
        }
        if (isset($form['img5']) && !empty($form['img5'])) {
            $indi->id(trim(esc_attr($form['img5'])));
            $is_update = true;
        }
        if (isset($form['img6']) && !empty($form['img6'])) {
            $indi->id(trim(esc_attr($form['img6'])));
            $is_update = true;
        }
        if (isset($form['img7']) && !empty($form['img7'])) {
            $indi->id(trim(esc_attr($form['img7'])));
            $is_update = true;
        }
        if (isset($form['cap1']) && !empty($form['cap1'])) {
            $indi->id(trim(esc_attr($form['cap1'])));
            $is_update = true;
        }
        if (isset($form['cap2']) && !empty($form['cap2'])) {
            $indi->id(trim(esc_attr($form['cap2'])));
            $is_update = true;
        }
        if (isset($form['cap3']) && !empty($form['cap3'])) {
            $indi->id(trim(esc_attr($form['cap3'])));
            $is_update = true;
        }
        if (isset($form['cap4']) && !empty($form['cap4'])) {
            $indi->id(trim(esc_attr($form['cap4'])));
            $is_update = true;
        }
        if (isset($form['cap5']) && !empty($form['cap5'])) {
            $indi->id(trim(esc_attr($form['cap5'])));
            $is_update = true;
        }
        if (isset($form['cap6']) && !empty($form['cap6'])) {
            $indi->id(trim(esc_attr($form['cap6'])));
            $is_update = true;
        }
        if (isset($form['cap7']) && !empty($form['cap7'])) {
            $indi->id(trim(esc_attr($form['cap7'])));
            $is_update = true;
        }
 * */
        return ( isset($options['errors']) ? array(false, $options) : array($indi, $options) );
    }
}