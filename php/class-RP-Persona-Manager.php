<?php
class Persona_Manager {

 	function process_form( $credentials, $parms, $options) {

        $validator = new Persona_Validator();
        $ret = $validator->validate( $parms, $options );
        $options = $ret[1];
        if($ret[0] !== false ) {
            $handler = new RP_Gedcom_Loader();
            $handler->credentials = $credentials;
            $handler->batch_id = $ret[0]->batch_id;
            $indi = $handler->process_individual( $ret[0] );
            if( $indi instanceof RP_Individual_Record ) {
                if( isset( $indi->id ) && !empty( $indi->id ) ) {
                    $page = $indi->page;
                    if( !isset( $page ) || empty( $page ) ) {
                        $name = $indi->names[0];
                        $title =   $name->rp_name->pieces->surname
                                . ', ' . $name->rp_name->pieces->given;
                        $contents = "[rootsPersona  personid='$indi->id' batchId='$indi->batch_id'/]";
                        $page_id = $this->add_page( $title, $contents, $options, '');
                        $indi->page = $page_id;
                    }
                }
            } else {
                $r = array();
                $r['error'] = 'Error saving record.';
                return $r;
            }
        } else {
            return $ret[1]['errors'];
        }
        $r = array();
        $r['rp_id'] = $indi->id;
        $r['rp_page'] = $indi->page;
        return $r;
	}

    function add_page( $title, $contents, $options, $page = '') {
        // Create post object
        $my_post = array();
        $my_post['post_title'] = $title;
        $my_post['post_content'] = $contents;
        $my_post['post_status'] = 'publish';
        $my_post['post_author'] = 0;
        $my_post['post_type'] = 'page';
        $my_post['ping_status'] = 'closed';
        $my_post['comment_status'] = 'closed';
        $my_post['post_parent'] = $options['headstones'];
        $page_id = '';
        if ( empty( $page ) ) {
            $page_id = wp_insert_post( $my_post );
        } else {
            $my_post['ID'] = $page;
            wp_update_post( $my_post );
            $page_id = $page;
        }
        return $page_id;
    }
}