<?php

require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Individual-Record.php' );

class Persona_Validator {

    public function validate($form, $options) {
        unset($options['errors']);
        $indi = new RP_Individual_Record();
        $is_update = false;
        if (isset($form['plot_id']) && !empty($form['plot_id'])) {
            $indi->set_id(trim(esc_attr($form['plot_id'])));
            $is_update = true;
        }

        if (isset($form['plot_status_grp'])
                && ctype_digit($form['plot_status_grp'])) {
            if ($is_update || $form['plot_status_grp'] != 0)
                $indi->set_status(intval($form['plot_status_grp']));
        } else {
            $options = $this->set_error('plot_status_grp', $options, sprintf(__('Status missing or invalid (%s).', 'rootspersona'), (isset($form['plot_status_grp']) ) ? '' : $form['plot_status_grp'] ));
        }

        if (isset($form['loc_img_file'])) {
            if ($is_update || !empty($form['loc_img_file'])) {

                if ((isset($form['loc_img_id']) && !empty($form['loc_img_id']) )
                        || !empty($form['loc_img_file'])) {
                    $indi->get_location_img()->set_url(trim(esc_attr($form['loc_img_file'])));
                }
                if (isset($form['loc_img_id']) && !empty($form['loc_img_id'])) {
                    $indi->get_location_img()->set_id(trim(esc_attr($form['loc_img_id'])));
                }
            }
        }

        return ( isset($options['errors']) ? array(false, $options) : array($indi, $options) );
    }
}