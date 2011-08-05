<?php
class RP_Xml_To_Database_Importer {
    /**
     *
     * @var RP_Credentials
     */
    var $credentials;

    /**
     *
     * @var array
     */
    var $pages;

    /**
     *
     * @param RP_Credentials $credentials
     * @param string $data_dir
     */
    function load_tables( $credentials, $data_dir ) {
        $this->credentials = $credentials;
        $dh = Opendir( $data_dir );
        while ( false !== ( $filename = readdir( $dh ) ) ) {
            if ( strpos( $filename, 'xml' ) <= 0
            || $filename == 'p000.xml'
            || $filename == 'templatePerson.xml'
            || $filename == 'f000.xml' )continue;
            $dom = new DOMDocument();
            $dom->load( $data_dir . '/' . $filename );
            $root = $dom->document_element;
            if ( isset( $root ) ) {
                if ( $root->tag_name == 'persona:person' ) {
                    $this->addPerson($dom);
                } elseif ( $root->tag_name == 'persona:familyGroup' ) {
                    $this->addFamily($dom);
                } elseif ( $root->tag_name == 'cite:evidence' ) {
                    $this->add_evidence( $dom );
                } elseif ( $root->tag_name == 'map:idMap' ) {
                    $this->addMappingData($dom);
                }
            }
            set_time_limit( 60 );
        }
        if ( $this->pages != null
        && count( $this->pages ) > 0 ) {
            $this->update_pages();
        }
        //then archive(?) and delete the files and data dir

    }

    /**
     *
     * @param DOM $dom
     */
    function add_person( $dom ) {
        $need_update = false;
        $root = $dom->document_element;
        $id = $root->get_attribute( 'id' );
        $c1 = $root->get_elements_by_tag_name( 'characteristics' );
        $c2 = $c1->item( 0 )->get_elements_by_tag_name( 'characteristic' );
        $gender = null;
        $name = null;
        $surname = null;
        for ( $idx = 0; $idx < $c2->length; $idx++ ) {
            $type = $c2->item( $idx )->get_attribute( 'type' );
            switch ( $type ) {
                case 'gender':
                    $gender = $c2->item( $idx )->node_value;
                break;
                case 'name':
                    $name = $c2->item( $idx )->node_value;
                break;
                case 'surname':
                    $surname = $c2->item( $idx )->node_value;
                break;
            }
        }
        $indi = new RP_Indi();
        $indi->id = $id;
        $indi->batch_id = 1;
        $indi->gender = $gender;try {
            $transaction = new RP_Transaction( $this->credentials );
            RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )->insert( $indi );
        } catch ( Exception $e ) {
            if ( stristr( $e->getMessage(), 'Duplicate entry' ) >= 0 ) {
                $need_update = true;
            } else {
                $transaction->rollback();
                echo $e->getMessage();
                throw $e;
            }
        }
        if ( $need_update ) {
            try {
                RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )->update( $indi );
            } catch ( Exception $e ) {
                $transaction->rollback();
                echo $e->getMessage();
                throw $e;
            }
        }
        $this->update_names( $id, $name, $surname );
        $this->update_indi_events( $id, $dom );
        $this->update_family_links( $id, $dom );
        $transaction->commit();
    }

    /**
     *
     * @param string $pid
     * @param string $fullname
     * @param string $surname
     */
    function update_names( $pid, $fullname, $surname ) {
        $old_names = RP_Dao_Factory::get_rp_indi_name_dao( $this->credentials->prefix )->load_list( $pid, 1 );
        if ( $old_names != null
        && count( $old_names ) > 0 ) {
            foreach ( $old_names as $name ) {
                RP_Dao_Factory::get_rp_name_personal_dao( $this->credentials->prefix )->delete( $name->name_id );
            }
            RP_Dao_Factory::get_rp_indi_name_dao( $this->credentials->prefix )->delete_by_indi_id( $pid, 1 );
        }
        $name = new RP_Name_Personal();
        $name->personal_name = $fullname;
        $name->surname = $surname == null ? 'Unknown' : $surname;
        if ( $surname != null ) {
            $name->given = Trim( str_replace( $surname, '', $fullname ) );
        }
        $id = null;
        try {
            $id = RP_Dao_Factory::get_rp_name_personal_dao( $this->credentials->prefix )->insert( $name );
        } catch ( Exception $e ) {
            echo $e->getMessage();
            throw $e;
        }
        $indi_name = new RP_Indi_Name();
        $indi_name->indi_id = $pid;
        $indi_name->indi_batch_id = 1;
        $indi_name->name_id = $id;try {
            RP_Dao_Factory::get_rp_indi_name_dao( $this->credentials->prefix )->insert( $indi_name );
        } catch ( Exception $e ) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     *
     * @param string $pid
     * @param DOM $dom
     */
    function update_indi_events( $pid, $dom ) {
        $family_fact_array =array( 'Annulment', 'Divorce', 'Divorce Filed', 'Engagement', 'Marriage Bann', 'Marriage Constract', 'Marriage', 'Marriage License', 'Marriage Settlement', 'Residence' );
        $old_events = RP_Dao_Factory::get_rp_indi_event_dao( $this->credentials->prefix )->load_list( $pid, 1 );
        if ( $old_events != null
        && count( $old_events ) > 0 ) {
            foreach ( $old_events as $eve ) {
                RP_Dao_Factory::get_rp_event_detail_dao( $this->credentials->prefix )->delete( $eve->event_id );
                RP_Dao_Factory::get_rp_event_cite_dao( $this->credentials->prefix )->delete_by_event_id( $eve->event_id );
                RP_Dao_Factory::get_rp_source_cite_dao( $this->credentials->prefix )->delete_orphans();
            }
            RP_Dao_Factory::get_rp_indi_event_dao( $this->credentials->prefix )->delete_by_indi_id( $pid, 1 );
        }
        $root = $dom->document_element;
        $c1 = $root->get_elements_by_tag_name( "events" );
        if ( $c1 != null
        && $c1->length > 0 ) {
            $c2 = $c1->item( 0 )->get_elements_by_tag_name( "event" );
            for ( $idx = 0; $idx < $c2->length; $idx++ ) {
                $type = $c2->item( $idx )->get_attribute( 'type' );
                $d = $c2->item( $idx )->get_elements_by_tag_name( "date" );
                $p = $c2->item( $idx )->get_elements_by_tag_name( "place" );
                $iid = null;
                if ( In_array( $type, $family_fact_array ) ) {
                    $indi = $c2->item( $idx )->get_elements_by_tag_name( "person" );
                    if ( $indi != null
                    && $indi->length > 0 ) {
                        $iid = $indi->item( 0 )->get_attribute( 'id' );
                    }
                }
                $event = new RP_Event_Detail();
                $event->event_type = $type;if ( $d != null
                && $d->length > 0 ) {
                    $event->event_date = $d->item( 0 )->node_value;
                }
                if ( $p != null
                && $p->length > 0 ) {
                    $event->place = $p->item( 0 )->node_value;
                }
                if ( $iid != null ) {
                    $event->classification = $pid . ':' . $iid;
                }
                $id = null;
                try {
                    $id = RP_Dao_Factory::get_rp_event_detail_dao( $this->credentials->prefix )->insert( $event );
                } catch ( Exception $e ) {
                    echo $e->getMessage();
                    throw $e;
                }
                $indi_event = new RP_Indi_Event();
                $indi_event->indi_id = $pid;
                $indi_event->indi_batch_id = 1;
                $indi_event->event_id = $id;try {
                    RP_Dao_Factory::get_rp_indi_event_dao( $this->credentials->prefix )->insert( $indi_event );
                } catch ( Exception $e ) {
                    echo $e->getMessage();
                    throw $e;
                }
                //$this->updateEventCitations($id, 1, $pEvent->Citations);

            }
        }
    }

    /**
     *
     * @param string $id
     * @param DOM $dom
     */
    function update_family_links( $id, $dom ) {
        RP_Dao_Factory::get_rp_indi_fam_dao( $this->credentials->prefix )->delete_by_indi( $id, 1 );
        $root = $dom->document_element;
        $c1 = $root->get_elements_by_tag_name( "references" );
        if ( $c1 != null
        && $c1->length > 0 ) {
            $c2 = $c1->item( 0 )->get_elements_by_tag_name( "familyGroups" );
            if ( $c2 == null
            && $c2->length > 0 ) {
                $c3 = $c2->item( 0 )->get_elements_by_tag_name( "familyGroup" );
                for ( $idx = 0; $idx < $c3->length; $idx++ ) {
                    $link_type = $c3->item( $idx )->get_attribute( 'selfType' );
                    $fid = $c3->item( $idx )->get_attribute( 'refId' );
                    $link = new RP_Indi_Fam();
                    $link->indi_id = $id;
                    $link->indi_batch_id = 1;
                    $link->fam_id = $fid;
                    $link->fam_batch_id = 1;
                    $link->link_type = $link_type == 'child' ? 'C' : 'S';
                    try {
                        RP_Dao_Factory::get_rp_indi_fam_dao( $this->credentials->prefix )->insert( $link );
                    } catch ( Exception $e ) {
                        echo $e->getMessage();
                        throw $e;
                    }
                }
            }
        }
    }

    /**
     *
     * @param DOM $dom
     */
    function add_family( $dom ) {
        $need_update = false;
        $root = $dom->document_element;
        $fid = $root->get_attribute( 'id' );
        $c1 = $root->get_elements_by_tag_name( "parents" );
        $c2 = $c1->item( 0 )->get_elements_by_tag_name( "relation" );
        for ( $idx = 0; $idx < $c2->length; $idx++ ) {
            $type = $c2->item( $idx )->get_attribute( 'type' );
            $p = $c2->item( $idx )->get_elements_by_tag_name( 'person' );
            $pid = $p->item( 0 )->get_attribute( 'id' );
            switch ( $type ) {
                case 'father':
                    $father = $pid;
                break;
                case 'mother':
                    $mother = $pid;
                break;
            }
        }
        $fam = new RP_Fam();
        $fam->id = $fid;
        $fam->batch_id = 1;
        $fam->spouse1 = $father;
        $fam->indi_batch_id1 = 1;
        $fam->spouse2 = $mother;
        $fam->indi_batch_id2 = 1;
        try {
            $transaction = new RP_Transaction( $this->credentials );
            RP_Dao_Factory::get_rp_fam_dao( $this->credentials->prefix )->insert( $fam );
        } catch ( Exception $e ) {
            if ( stristr( $e->getMessage(), 'Duplicate entry' ) >= 0 ) {
                $need_update = true;
            } else {
                $transaction->rollback();
                echo $e->getMessage();
                throw $e;
            }
        }
        if ( $need_update ) {
            try {
                RP_Dao_Factory::get_rp_fam_dao( $this->credentials->prefix )->update( $fam );
            } catch ( Exception $e ) {
                $transaction->rollback();
                echo $e->getMessage();
                throw $e;
            }
        }
        $this->update_children( $fid, $dom );
        //$this->updateFamEvents($dom);
        $transaction->commit();
    }

    /**
     *
     * @param string $fid
     * @param DOM $dom
     */
    function update_children( $fid, $dom ) {
        RP_Dao_Factory::get_rp_fam_child_dao( $this->credentials->prefix )->delete_children( $fid, 1 );
        $root = $dom->document_element;
        $c1 = $root->get_elements_by_tag_name( "children" );
        $c2 = $c1->item( 0 )->get_elements_by_tag_name( "relation" );
        for ( $idx = 0; $idx < $c2->length;     $idx++ ) {
            $type = $c2->item( $idx )->get_attribute( 'type' );
            $p = $c2->item( $idx )->get_elements_by_tag_name( 'person' );
            $pid = $p->item( 0 )->get_attribute( 'id' );
            if ( $type == 'child' ) {
                $fam_child = new RP_Fam_Child();
                $fam_child->fam_id = $fid;
                $fam_child->fam_batch_id = 1;
                $fam_child->child_id = $pid;
                $fam_child->indi_batch_id = 1;
                try {
                    $id = RP_Dao_Factory::get_rp_fam_child_dao( $this->credentials->prefix )->insert( $fam_child );
                } catch ( Exception $e ) {
                    echo $e->getMessage();
                    throw $e;
                }
            }
        }
    }

    /**
     *
     * @param DOM $dom
     */
    function add_evidence( $dom ) {
        try {
            $transaction = new RP_Transaction( $this->credentials );
            // only cause we are upgrading
            RP_Dao_Factory::get_rp_indi_cite_dao( $this->credentials->prefix )->clean();
            $transaction->commit();
        } catch ( Exception $e ) {
            $transaction->rollback();
            echo $e->getMessage();
            throw $e;
        }
        $root = $dom->document_element;
        $c1 = $root->get_elements_by_tag_name( "source" );
        for ( $idx = 0; $idx < $c1->length; $idx++ ) {
            $need_update = false;
            $src_id = $c1->item( $idx )->get_attribute( 'sourceId' );
            $page_id = $c1->item( $idx )->get_attribute( 'pageId' );
            $a1 = null;
            $t1 = null;
            $a1 = $c1->item( $idx )->get_elements_by_tag_name( "abbr" );
            if ( $a1 != null
            && $a1->length > 0 ) {
                $abbr = $a1->item( 0 )->node_value;
            }
            $t1 = $c1->item( $idx )->get_elements_by_tag_name( "title" );
            if ( $t1 != null
            && $t1->length > 0 ) {
                $title = $t1->item( 0 )->node_value;
            }
            $src = new RP_Source();
            $src->id = $src_id;
            $src->batch_id = 1;
            $src->wp_page_id = $page_id;
            $src->source_title = $title;
            $src->abbr = $abbr;
            try {
                $transaction = new RP_Transaction( $this->credentials );
                RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )->insert( $src );
            } catch ( Exception $e ) {
                if ( stristr( $e->getMessage(), 'Duplicate entry' ) >= 0 ) {
                    $need_update = true;
                } else {
                    $transaction->rollback();
                    echo $e->getMessage();
                    throw $e;
                }
            }
            if ( $need_update ) {
                try {
                    RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )->update( $src );
                } catch ( Exception $e ) {
                    $transaction->rollback();
                    echo $e->getMessage();
                    throw $e;
                }
            }
            $this->add_citations( $src_id, $c1->item( $idx ) );
            $transaction->commit();
        }
    }

    /**
     *
     * @param string $src_id
     * @param DOM $doc
     */
    function add_citations( $src_id, $doc ) {
        RP_Dao_Factory::get_rp_source_cite_dao( $this->credentials->prefix )->delete_by_src( $src_id, 1 );
        $c1 = $doc->get_elements_by_tag_name( "citation" );
        for ( $idx = 0; $idx < $c1->length; $idx++ ) {
            $detail = null;
            $c2 = $c1->item( $idx )->get_elements_by_tag_name( "detail" );
            if ( $c2 != null
            && $c2->length > 0 ) {
                $detail = $c2->item( 0 )->node_value;
            }
            $cite = new RP_Source_Cite();
            $cite->source_id = $src_id;
            $cite->source_batch_id = 1;
            $cite->source_page = $detail;try {
                $id = RP_Dao_Factory::get_rp_source_cite_dao( $this->credentials->prefix )->insert( $cite );
                $c3 = $c1->item( $idx )->get_elements_by_tag_name( "person" );
                for ( $idx2 = 0; $idx2 < $c3->length; $idx2++ ) {
                    $person_id = $c3->item( $idx2 )->get_attribute( 'id' );
                    $indi_cite = new RP_Indi_Cite();
                    $indi_cite->indi_id = $person_id;
                    $indi_cite->indi_batch_id = 1;
                    $indi_cite->cite_id = $id;try {
                        RP_Dao_Factory::get_rp_indi_cite_dao( $this->credentials->prefix )->insert( $indi_cite );
                    } catch ( Exception $e ) {
                        echo $e->getMessage();
                        throw $e;
                    }
                }
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     *
     * @param type $dom
     */
    function add_mapping_data( $dom ) {
        $pages = array();
        $root = $dom->document_element;
        $e1 = $root->get_elements_by_tag_name( "entry" );
        for ( $idx = 0; $idx < $e1->length; $idx++ ) {
            $page_id = $e1->item( $idx )->get_attribute( 'pageId' );
            if ( $page_id != null
            && ! empty( $page_id ) ) {
                $person_id = $e1->item( $idx )->get_attribute( 'personId' );
                $this->pages[$idx] = array( $person_id, $page_id );
            }
        }
    }

    /**
     *
     */
    function update_pages() {
        for ( $idx = 0; $idx < count( $this->pages ); $idx++ ) {
            $page_id = $this->pages[$idx][1];
            if ( $page_id != null
            && ! empty( $page_id ) ) {
                $person_id = $this->pages[$idx][0];
                try {
                    $transaction = new RP_Transaction( $this->credentials );
                    RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )->update_page( $person_id, 1, $page_id );
                    $transaction->commit();
                } catch ( Exception $e ) {
                    echo $e->getMessage();
                    $transaction->rollback();
                    throw $e;
                }
            }
        }
    }
}
?>
