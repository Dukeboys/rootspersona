<?php
class Persona_Manager {

 	function process_form( $credentials, $parms, $options) {

        $validator = new Persona_Validator();
        $ret = $validator->validate( $parms, $options );
        $options = $ret[1];
        if($ret[0] !== false ) {
            $handler = new RP_Gedcom_Loader();
            $indi = $handler->process_individual( $ret[0] );
            if( $indi instanceof RP-Individual-Record ) {
                if( isset( $indi->id ) && !empty( $indi->id ) ) {
//                    $page = $indi->get_deceased()->get_page();
//                    if( !isset( $page ) || empty( $page ) ) {
//                        $name = $indi->getName();
//                        $title =   $name->surname
//                                . ', ' . $name->given();
//                        $contents = "[rotospersona personid='$pid'/]";
//                        $page_id = $this->add_page( $title, $contents, $options, '');
//                        $indi->get_deceased()->set_page($page_id);
//                        $handler->update_page($indi->get_deceased());
//                    }
                }
            } else {
                $options['errors']['location'] = $indi->getMessage();
                $indi = $ret[0];
            }
        } else {
            return false;
        }
        return true;
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