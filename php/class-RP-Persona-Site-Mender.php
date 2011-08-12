<?php

class RP_Persona_Site_Mender {
    /**
     *
     * @var RP_Credentials
     */
    var $credentials;

    /**
     *
     * @param RP_Credentials $credentials
     */
    function __construct( $credentials ) {
        $this->credentials = $credentials;
    }

    /**
     *
     * @param array $options
     * @param boolean $is_repair
     */
    function validate_pages( $options, $is_repair = false ) {
        $args = array( 'numberposts' => - 1, 'post_type' => 'page', 'post_status' => 'any' );
        $pages = get_posts( $args );
        $is_first = true;
        $is_empty = count($pages)>0 ? false : true;

        $queryOnly = $is_repair === true ? false : true;
        $transaction = new RP_Transaction( $this->credentials, $queryOnly );
        $parent = $options['parent_page'];
        foreach ( $pages as $page ) {
            $output = array();
            if ( preg_match( "/rootsPersona /i", $page->post_content ) ) {

                $pid = @preg_replace( '/.*?personId=[\'|"](.*)[\'|"].*?/US', '$1'
                        , $page->post_content );
                $wp_page_id = RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )
                        ->get_page_id( $pid, 1 );
                if ( ! isset( $wp_page_id ) || $wp_page_id == null ) {
                    if ( $is_repair ) {
                        $output[] =
                            __( "Deleted orphaned page with no reference in",
                                'rootspersona' )  . ' rp_indi.';;
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "No reference in", 'rootspersona' ) . ' rp_indi.';
                    }
                } else if ( $page->post_parent != $parent ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Updated parent page to %s.", 'rootspersona' )
                                , $parent );
                        $my_post = array();
                        $my_post['ID'] = $page->ID;
                        $my_post['post_parent'] = $parent;
                        wp_update_post( $my_post );
                    } else {
                        $output[] = sprintf( __( "Parent page out of synch %s", 'rootspersona' )
                                , " (" . $parent . ")." );
                    }
                }
            } else if ( preg_match( "/rootsPersonaIndexPage/i", $page->post_content ) ) {
                $page_id = $options['index_page'];
                if ( $page_id != $page->ID ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Deleted orphaned %s page.", 'rootspersona' ),
                                "rootsPersonaIndexPage" );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "Orphaned", 'rootspersona' )
                                    . " rootsPersonaIndexPage.";
                    }
                }
            } else if ( preg_match( "/rootsEditPersonaForm/i", $page->post_content ) ) {
                $page_id = $options['edit_page'];
                if ( $page_id != $page->ID ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Deleted orphaned %s page.", 'rootspersona' ),
                                "rootsEditPersonaForm" );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "Orphaned", 'rootspersona' ) . " rootsEditPersonaForm.";
                    }
                }
            } else if ( preg_match( "/rootsAddPageForm/i", $page->post_content ) ) {
                $page_id = $options['create_page'];
                if ( $page_id != $page->ID ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Deleted orphaned %s page.", 'rootspersona' ),
                                "rootsAddPageForm" );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "Orphaned", 'rootspersona' ) . " rootsAddPageForm.";
                    }
                }
            } else if ( preg_match( "/rootsUploadGedcomForm/i", $page->post_content ) ) {
                $page_id = $options['upload_gedcom_page'];
                if ( $page_id != $page->ID ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Deleted orphaned %s page.", 'rootspersona' ),
                                'rootsUploadGedcomForm' );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "Orphaned", 'rootspersona' ) . " rootsUploadGedcomForm.";
                    }
                }
            } else if ( preg_match( "/rootsIncludePageForm/i", $page->post_content ) ) {
                $page_id = $options['include_page'];
                if ( $page_id != $page->ID ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Deleted orphaned %s page.", 'rootspersona' ),
                                'rootsIncludePage' );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "Orphaned", 'rootspersona' ) . " rootsIncludePage.";
                    }
                }
            } else if ( preg_match( "/rootsUtilityPage/i", $page->post_content ) ) {
                $page_id = $options['utility_page'];
                if ( $page_id != $page->ID ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Deleted orphaned %s page.", 'rootspersona' ),
                                'rootsUtilityPage' );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "Orphaned", 'rootspersona' ) . " rootsUtilityPage.";
                    }
                }
            } else if ( preg_match( "/rootsEvidencePage *sourceId=.*/i", $page->post_content ) ) {
                $sid = @preg_replace( '/.*?sourceId=[\'|"](.*)[\'|"].*?/US', '$1', $page->post_content );
                $wp_page_id = RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )
                        ->get_page_id( $sid, 1 );
                if ( ! isset( $wp_page_id ) || $wp_page_id == null ) {
                    if ( $is_repair ) {
                        $output[] = __( "Deleted orphaned page with no reference in rp_source.",
                                'rootspersona' );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "No reference in rp_source.", 'rootspersona' );
                    }
                } else if ( $page->post_parent != $parent ) {
                    if ( $is_repair ) {
                        $output[] = sprintf( __( "Updated parent page to %s.", 'rootspersona' ),
                                $parent );
                        $my_post = array();
                        $my_post['ID'] = $page->ID;
                        $my_post['post_parent'] = $parent;
                        wp_update_post( $my_post );
                    } else {
                        $output[] = sprintf( __( "Parent page out of synch %s", 'rootspersona' ),
                                " (" . $parent . ")." );
                    }
                }
            } else if ( preg_match( "/rootsEvidencePage.*/i", $page->post_content ) ) {
                if ( $page->ID != $options['evidence_page'] ) {
                    if ( $is_repair ) {
                        $output[] = __( "Deleted orphaned evidence index page.",'rootspersona' );
                        wp_delete_post( $page->ID );
                    } else {
                        $output[] = __( "Orphan evidence index.", 'rootspersona' );
                    }
                }
            }
            foreach ( $output as $line ) {
                if ( $is_first ) {
                    echo "<p style='padding:0.5em;background-color:yellow;color:black;font-weight:bold;'>"
                    . sprintf( __( 'Issues found with your %s pages.', 'rootspersona' ), "rootsPersona" )
                    . "</p>";
                    $is_first = false;
                }
                echo __( "Page", 'rootspersona' ) . ' ' . $page->ID . ": " . $line . "<br/>";
            }
        }
        $expected_pages = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                ->get_persons_with_pages();
        foreach( $expected_pages AS $expected ) {

            $output = array();
            $page = get_post( $expected['page_id'], ARRAY_A );

            if(empty($page) || $page['post_status'] == 'trash' ) {
                 if ( $is_repair ) {
                    $output[] = __( 'Database updated to remove reference to missing page.', 'rootspersona' );
                    RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )
                            ->update_page( $expected['id'], $expected['batch_id'], null );
                } else {
                    $output[] = __( 'Invalid person page assignment in database (page missing).', 'rootspersona' );
                }
            } else  {
                $pid = @preg_replace( '/.*?personId=[\'|"](.*)[\'|"].*?/US', '$1', $page['post_content'] );
                if ( empty( $pid ) ) {
                    if ( $is_repair ) {
                        $output[] = __( 'Database updated to remove reference to invalid page.', 'rootspersona' );
                        RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )
                                ->update_page( $expected['id'], $expected['batch_id'], null );
                    } else {
                        $output[] = __( 'Invalid person page assignment in database (no personid).', 'rootspersona' );
                    }
                } else if ( $pid != $expected['id'] ) {
                    if ( $is_repair ) {
                        $output[] = __( 'Database updated to remove reference to invalid page.', 'rootspersona' );
                        RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )
                                ->update_page( $expected['id'], $expected['batch_id'], null );
                    } else {
                        $output[] = __( 'Invalid person page assignment in database (wrong personId).', 'rootspersona' );
                    }
                } else if ( $page['post_title'] != $expected['name'] ) {
                    if ( $is_repair ) {
                        $output[] = __( 'Page title updated.', 'rootspersona' );
                        $my_post = array();
                        $my_post['ID'] = $page['ID'];
                        $my_post['post_title'] = $expected['name'];
                        wp_update_post( $my_post );
                    } else {
                        $output[] = __( 'Page title out of synch with person name', 'rootspersona' )
                                . ' (' . $page['post_title'] . ' : ' . $expected['name'];
                    }
                }
            }

            foreach ( $output as $line ) {
                if ( $is_first ) {
                    echo "<p style='padding:0.5em;background-color:yellow;color:black;font-weight:bold;'>"
                    . sprintf( __( 'Issues found with your %s pages.', 'rootspersona' ), "rootsPersona" )
                    . "</p>";
                    $is_first = false;
                }
                echo __( "Page", 'rootspersona' ) . ' ' . $expected['page_id']. ": " . $line . "<br/>";
            }
        }

        $expected_pages = RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )->get_sources_with_pages();
        if ( isset ( $expected_pages ) && count( $expected_pages ) > 0 ) {
            foreach( $expected_pages AS $expected ) {

                $output = array();
                $page = get_post( $expected['page_id'], ARRAY_A );

                if(empty($page) || $page['post_status'] == 'trash' ) {
                     if ( $is_repair ) {
                        $output[] = __( 'Database updated to remove reference to missing page.', 'rootspersona' );
                        RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )
                                ->update_page( $expected['id'], $expected['batch_id'], null );
                    } else {
                        $output[] = __( 'Invalid source page assignment in database (page missing).', 'rootspersona' );
                    }
                } else  {
                    $sid = @preg_replace( '/.*?sourceId=[\'|"](.*)[\'|"].*?/US', '$1', $page['post_content'] );
                    if ( empty( $sid ) ) {
                        if ( $is_repair ) {
                            $output[] = __( 'Database updated to remove reference to invalid page.', 'rootspersona' );
                            RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )
                                    ->update_page( $expected['id'], $expected['batch_id'], null );
                        } else {
                            $output[] = __( 'Invalid source page assignment in database (no sourceid).', 'rootspersona' );
                        }
                    } else if ( $sid != $expected['id'] ) {
                        if ( $is_repair ) {
                            $output[] = __( 'Database updated to remove reference to invalid page.', 'rootspersona' );
                            RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )
                                    ->update_page( $expected['id'], $expected['batch_id'], null );
                        } else {
                            $output[] = __( 'Invalid source page assignment in database (wrong sourceId).', 'rootspersona' );
                        }
                    } else if ( $page['post_title'] != $expected['title'] ) {
                        if ( $is_repair ) {
                            $output[] = __( 'Page title updated.', 'rootspersona' );
                            $my_post = array();
                            $my_post['ID'] = $page['ID'];
                            $my_post['post_title'] = $expected['title'];
                            wp_update_post( $my_post );
                        } else {
                            $output[] = __( 'Page title out of synch with person name', 'rootspersona' )
                                    . ' (' . $page['post_title'] . ' : ' . $expected['title'];
                        }
                    }
                }

                foreach ( $output as $line ) {
                    if ( $is_first ) {
                        echo "<p style='padding:0.5em;background-color:yellow;color:black;font-weight:bold;'>"
                        . sprintf( __( 'Issues found with your %s pages.', 'rootspersona' ), "rootsPersona" )
                        . "</p>";
                        $is_first = false;
                    }
                    echo __( "Page", 'rootspersona' ) . ' ' . $expected['page_id'] . ": " . $line . "<br/>";
                }
            }
        }

        if( $queryOnly ) $transaction->close();
        else $transaction->commit();

        $footer = "<div style='text-align:center;padding:.5em;margin-top:.5em;'>";
        if ( $is_empty ) {
            $footer .= "<p style='padding:0.5em;margin-top:.5em;background-color:green;color:white; font-weight:bold;'>"
                    . __( 'No persona pages found.', 'rootspersona' )
                    . "</p><span>&#160;&#160;</span>";
        } else if ( $is_first ) {
            $footer .= "<p style='padding:0.5em;margin-top:.5em;background-color:green;color:white;font-weight:bold;'>"
                    . sprintf( __( 'Your %s setup is VALID.', 'rootspersona' ),
                            "rootsPersona" )
                    . "</p><span>&#160;&#160;</span>";
        } else if ( ! $is_repair ) {
            $footer .= "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' "
                . home_url() . "?page_id=" . $options['utility_page']
                . "&utilityAction=repairPages'>"
                . __( 'Repair Inconsistencies?', 'rootspersona' )
                . "</a></span><span>&#160;&#160;</span>";
        }

        $footer .= "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
                . admin_url() . "tools.php?page=rootsPersona'>"
                . __( 'Return', 'rootspersona' )
                . "</a></span></div>";
        echo $footer;
    }

    /**
     *
     * @param array $options
     * @return string
     */
    function delete_pages( $options ) {
        $args = array( 'numberposts' => - 1, 'post_type' => 'page', 'post_status' => 'any' );
        $pages = Get_posts( $args );
        $cnt = 0;
        foreach ( $pages as $page ) {
            if ( preg_match( "/rootsPersona |rootsEvidencePage /", $page->post_content ) ) {
                wp_delete_post( $page->ID );
                $cnt++;
                Set_time_limit( 60 );
            }
        }
        $transaction = new RP_Transaction( $this->credentials, false );
        RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )->unlink_all_pages();
        RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )->unlink_all_pages();
        $transaction->commit();
        $block =  $cnt . ' ' . __( 'pages deleted.', 'rootspersona' ) . "<br/>"
            . "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
            . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
            . Admin_url() . "tools.php?page=rootsPersona'>"
            . __( 'Return', 'rootspersona' ) . "</a></span>"
            . "</div>";
        return $block;
    }

    /**
     *
     * @param array $options
     * @return string
     */
    function add_evidence_pages( $options ) {
        $batch_id = 1;
        $transaction = new RP_Transaction( $this->credentials, false );
        $sources = RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )
                ->get_source_no_page( $batch_id );
        foreach ( $sources AS $src ) {
            $content = sprintf( "[rootsEvidencePage sourceId='%s' batchId='%s'/]", $src['id'], $batch_id );
            $page_id = RP_Persona_Helper::create_evidence_page( $src['title'], $content, $options );
            RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )
                    ->update_page( $src['id'], 1, $page_id );
        }
        $transaction->commit();
        return count($sources) . ' ' . __('source pages added','rootspersona' ) . '.<br/>';
    }
}
?>
