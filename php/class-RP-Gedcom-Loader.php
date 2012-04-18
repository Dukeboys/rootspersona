<?php
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Entity-Abstract.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Record-Abstract.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/class-RP-Parser.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Exceptions/class-RP-File-Exception.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Exceptions/class-RP-Invalid-Field-Exception.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/class-RP-Gedcom-Manager.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Fact-Detail.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Family-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Header-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Individual-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Media-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Note-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Repository-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Source-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Submission-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Records/class-RP-Submitter-Record.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Address.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Association.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Change-Date.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Character-Set.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Citation.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Corporation.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Data.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Event.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Fact.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Family-Link.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Ged-C.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Lds-Ordinance.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Lds-Sealing.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Media-File.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Media-Link.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Name.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Name-Pieces.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Personal-Name.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Place.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Repository-Citation.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Source-Data.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/Structures/class-RP-Source-System.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/Genealogy/Gedcom/class-RP-Tags.php' );

class RP_Gedcom_Loader {
    /**
     *
     * @var RP_Gedcom_Manager
     */
    var $ged;

    /**
     *
     * @var RP_Credentials
     */
    var $credentials;

    /**
     *
     * @var integer
     */
    var $batch_id;

    /**
     *
     * @param RP_Credentials $credentials
     * @param string $gedcom_file
     */
    function load_tables( $credentials, $gedcom_file, $batch_id ) {
        $this->batch_id = $batch_id;
        $this->credentials = $credentials;
        $this->ged = new RP_Gedcom_Manager();
        $this->ged->parse( $gedcom_file, $this );
    }

    /**
     *
     * @param RP_Submitter_Record $rec
     */
    function add_subm( $rec ) {
    }

    /**
     *
     * @param RP_Submission_Record $rec
     */
    function add_subn( $rec ) {
    }

    /**
     *
     * @param RP_Header_Record $rec
     */
    function add_hdr( $rec ) {
    }

    /**
     *
     * @param RP_Note_Record $note
     */
    function add_note_rec( $note ) {
        $need_update = false;
        $note_rec = new RP_Note_Rec();
        $note_rec->id = $note->id;
        $note_rec->batch_id = $this->batch_id;
        $note_rec->submitter_text = $note->text;

        try {
            $transaction = new RP_Transaction( $this->credentials );
            RP_Dao_Factory::get_rp_note_dao( $this->credentials->prefix )->insert( $note_rec );
        } catch ( Exception $e ) {
            if ( stristr( $e->getMessage(), 'Duplicate entry' ) >= 0 ) {
                $need_update = true;
            } else {
                echo $e->getMessage();
                throw $e;
            }
        }
        if ( $need_update ) {
            try {
                RP_Dao_Factory::get_rp_note_dao( $this->credentials->prefix )->update( $note_rec );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }

        $transaction->commit();
    }

    /**
     *
     * @param RP_Individual_Record $person
     */
    function add_indi( $person ) {
        $need_update = false;
        $indi = new RP_Indi();
        $indi->id = $person->id;
        $indi->batch_id = $this->batch_id;
        $indi->restriction_notice = $person->restriction;
        $indi->gender = $person->gender;
        $indi->perm_rec_file_nbr = $person->perm_rec_file_nbr;
        $indi->anc_rec_file_nbr = $person->anc_file_nbr;
        $indi->auto_rec_id = $person->auto_rec_id;
        $indi->ged_change_date = $person->change_date->date;
        try {
            $transaction = new RP_Transaction( $this->credentials );
            RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )->insert( $indi );
        } catch ( Exception $e ) {
            if ( stristr( $e->getMessage(), 'Duplicate entry' ) >= 0 ) {
                $need_update = true;
            } else {
                echo $e->getMessage();
                throw $e;
            }
        }
        if ( $need_update ) {
            try {
                RP_Dao_Factory::get_rp_indi_dao( $this->credentials->prefix )->update( $indi );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
        $this->update_names( $person );
        $this->update_indi_events( $person );
        $this->update_family_links( $person );
        $this->update_notes( $person );
        $transaction->commit();
        return $indi;
    }

    /**
     *
     * @param RP_Individual_Record $person
     */
    function update_notes( $person ) {
        RP_Dao_Factory::get_rp_indi_note_dao( $this->credentials->prefix )
                ->delete_by_indi_id($person->id, $this->batch_id );
        if( isset ( $person->notes ) && count( $person->notes ) > 0 ) {
            foreach ( $person->notes AS $note ) {
                $new_note = new RP_Indi_Note();
                $new_note->indi_batch_id = $this->batch_id;
                $new_note->indi_id = $person->id;
                $new_note->note_rec_id = $note->id;
                $new_note->note = $note->text;
               try {
                $note->id = RP_Dao_Factory::get_rp_indi_note_dao( $this->credentials->prefix )
                       ->insert($new_note);
               } catch ( Exception $e ) {
                    echo $e->getMessage();
                    throw $e;
               }
            }
        }
    }

    /**
     *
     * @param RP_Individual_Record $person
     */
    function update_family_links( $person ) {
        RP_Dao_Factory::get_rp_indi_fam_dao( $this->credentials->prefix )->delete_by_indi( $person->id, $this->batch_id  );
        foreach ( $person->spouse_family_links as $spousal ) {
            $link = new RP_Indi_Fam();
            $link->indi_id = $person->id;
            $link->indi_batch_id = $this->batch_id;
            $link->fam_id = $spousal->family_id;
            $link->fam_batch_id = $this->batch_id;
            $link->link_type = 'S';
            try {
                RP_Dao_Factory::get_rp_indi_fam_dao( $this->credentials->prefix )->insert( $link );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
        foreach ( $person->child_family_links as $child ) {
            $link = new RP_Indi_Fam();
            $link->indi_id = $person->id;
            $link->indi_batch_id = $this->batch_id;
            $link->fam_id = $child->family_id;
            $link->fam_batch_id = $this->batch_id;
            $link->link_type = 'C';
            $link->link_status = $child->linkage_status;
            $link->pedigree = $child->linkage_type;
            try {
                RP_Dao_Factory::get_rp_indi_fam_dao( $this->credentials->prefix )->insert( $link );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     *
     * @param RP_Individual_Record $person
     */
    function update_indi_events( $person ) {
        $old_events = RP_Dao_Factory::get_rp_indi_event_dao( $this->credentials->prefix )->load_list( $person->id, $this->batch_id );
        if ( $old_events != null
        && count( $old_events ) > 0 ) {
            foreach ( $old_events as $eve ) {
                RP_Dao_Factory::get_rp_event_detail_dao( $this->credentials->prefix )->delete( $eve->event_id );
                RP_Dao_Factory::get_rp_event_cite_dao( $this->credentials->prefix )->delete_by_event_id( $eve->event_id );
                RP_Dao_Factory::get_rp_source_cite_dao( $this->credentials->prefix )->delete_orphans();
            }
            RP_Dao_Factory::get_rp_indi_event_dao( $this->credentials->prefix )->delete_by_indi_id( $person->id, $this->batch_id );
        }
        foreach ( $person->events as $p_event ) {
            $event = new RP_Event_Detail();
            $event->event_type = ( $p_event->tag === 'EVEN' ? $p_event->type : $p_event->_TYPES[$p_event->tag] );
            $event->classification = $p_event->descr;
            $event->event_date = $p_event->date;
            $event->place = $p_event->place->name;
            //$event->addrId;
            $event->resp_agency = $p_event->resp_agency;
            $event->religious_aff = $p_event->religious_affiliation;
            $event->cause = $p_event->cause;
            $event->restriction_notice = $p_event->restriction;
            $id = null;
            try {
                $id = RP_Dao_Factory::get_rp_event_detail_dao( $this->credentials->prefix )->insert( $event );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $indi_event = new RP_Indi_Event();
            $indi_event->indi_id = $person->id;
            $indi_event->indi_batch_id = $this->batch_id;
            $indi_event->event_id = $id;try {
                RP_Dao_Factory::get_rp_indi_event_dao( $this->credentials->prefix )->insert( $indi_event );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $this->update_event_citations( $id, $this->batch_id, $p_event->citations );
        }

        foreach ( $person->attributes as $p_event ) {
            $event = new RP_Event_Detail();
            $event->event_type = ( $p_event->tag === 'FACT' ? $p_event->type : $p_event->_TYPES[$p_event->tag] );
            $event->classification = $p_event->descr;
            $event->event_date = $p_event->date;
            $event->place = $p_event->place->name;
            //$event->addrId;
            $event->resp_agency = $p_event->resp_agency;
            $event->religious_aff = $p_event->religious_affiliation;
            $event->cause = $p_event->cause;
            $event->restriction_notice = $p_event->restriction;
            $id = null;
            try {
                $id = RP_Dao_Factory::get_rp_event_detail_dao( $this->credentials->prefix )->insert( $event );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $indi_event = new RP_Indi_Event();
            $indi_event->indi_id = $person->id;
            $indi_event->indi_batch_id = $this->batch_id;
            $indi_event->event_id = $id;try {
                RP_Dao_Factory::get_rp_indi_event_dao( $this->credentials->prefix )->insert( $indi_event );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $this->update_event_citations( $id, $this->batch_id, $p_event->citations );
        }
    }

    /**
     *
     * @param RP_Individual_Record $person
     */
    function update_names( $person ) {
        $old_names = RP_Dao_Factory::get_rp_indi_name_dao( $this->credentials->prefix )->load_list( $person->id, $this->batch_id );
        if ( $old_names != null
        && count( $old_names ) > 0 ) {
            foreach ( $old_names as $name ) {
                RP_Dao_Factory::get_rp_name_personal_dao( $this->credentials->prefix )->delete( $name->name_id );
            }
            RP_Dao_Factory::get_rp_indi_name_dao( $this->credentials->prefix )->delete_by_indi_id( $person->id, $this->batch_id );
        }
        $seq = 1;
        foreach ( $person->names as $p_name ) {
            $name = new RP_Name_Personal();
            $name->personal_name = $p_name->get_name();
            $name->name_type = $p_name->rp_name->type;
            $name->prefix = $p_name->rp_name->pieces->prefix;
            $g_name = $p_name->rp_name->get_given();
            $name->given = $g_name === null ? '' : $g_name;
            $name->nickname = $p_name->rp_name->pieces->nick_name;
            $name->surname_prefix = $p_name->rp_name->pieces->surname_prefix;
            $s_name = $p_name->rp_name->get_surname();
            $name->surname = $s_name === null ? '' : $s_name;
            $name->suffix = $p_name->rp_name->pieces->suffix;
            $id = null;
            try {
                $id = RP_Dao_Factory::get_rp_name_personal_dao( $this->credentials->prefix )->insert( $name );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $indi_name = new RP_Indi_Name();
            $indi_name->indi_id = $person->id;
            $indi_name->indi_batch_id = $this->batch_id;
            $indi_name->name_id = $id;
            $indi_name->seq_nbr = $seq++;
            try {
                $id = RP_Dao_Factory::get_rp_indi_name_dao( $this->credentials->prefix )->insert( $indi_name );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     *
     * @param RP_Family_Record $family
     */
    function add_fam( $family ) {
        $need_update = false;
        $fam = new RP_Fam();
        $fam->id = $family->id;
        $fam->batch_id = $this->batch_id;
        $fam->restriction_notice = $family->restriction;
        $fam->spouse1 = $family->husband;
        $fam->indi_batch_id1 = $this->batch_id;
        $fam->spouse2 = $family->wife;
        $fam->indi_batch_id2 = $this->batch_id;
        $fam->auto_rec_id = $family->auto_rec_id;
        $fam->ged_change_date = $family->change_date->date;
        try {
            $transaction = new RP_Transaction( $this->credentials );
            RP_Dao_Factory::get_rp_fam_dao( $this->credentials->prefix )->insert( $fam );
        } catch ( Exception $e ) {
            if ( stristr( $e->getMessage(), 'Duplicate entry' ) >= 0 ) {
                $need_update = true;
            } else {
                echo $e->getMessage();
                throw $e;
            }
        }
        if ( $need_update ) {
            try {
                RP_Dao_Factory::get_rp_fam_dao( $this->credentials->prefix )->update( $fam );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
        $this->update_children( $family );
        $this->update_fam_events( $family );
        $transaction->commit();
    }

    /**
     *
     * @param RP_Family_Record $family
     */
    function update_children( $family ) {
        RP_Dao_Factory::get_rp_fam_child_dao( $this->credentials->prefix )->delete_children( $family->id, $this->batch_id);
        foreach ( $family->children as $child ) {
            $fam_child = new RP_Fam_Child();
            $fam_child->fam_id = $family->id;
            $fam_child->fam_batch_id = $this->batch_id;
            $fam_child->child_id = $child;
            $fam_child->indi_batch_id = $this->batch_id;
            try {
                $id = RP_Dao_Factory::get_rp_fam_child_dao( $this->credentials->prefix )->insert( $fam_child );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     *
     * @param RP_Family_Record $family
     */
    function update_fam_events( $family ) {
        $old_events = RP_Dao_Factory::get_rp_fam_event_dao( $this->credentials->prefix )->load_list( $family->id, $this->batch_id );
        if ( $old_events != null
        && count( $old_events ) > 0 ) {
            foreach ( $old_events as $eve ) {
                RP_Dao_Factory::get_rp_event_detail_dao( $this->credentials->prefix )->delete( $eve->event_id );
                RP_Dao_Factory::get_rp_event_cite_dao( $this->credentials->prefix )->delete_by_event_id( $eve->event_id );
                RP_Dao_Factory::get_rp_source_cite_dao( $this->credentials->prefix )->delete_orphans();
            }
            RP_Dao_Factory::get_rp_fam_event_dao( $this->credentials->prefix )->delete_by_fam( $family->id, $this->batch_id );
        }
        foreach ( $family->events as $p_event ) {
            $event = new RP_Event_Detail();
            $event->event_type = ( $p_event->tag === 'EVEN' ? $p_event->type : $p_event->_TYPES[$p_event->tag] );
            $event->classification = $p_event->descr;
            $event->event_date = $p_event->date;
            $event->place = $p_event->place->name;
            //$event->addrId;
            $event->resp_agency = $p_event->resp_agency;
            $event->religious_aff = $p_event->religious_affiliation;
            $event->cause = $p_event->cause;
            $event->restriction_notice = $p_event->restriction;
            $id = null;
            try {
                $id = RP_Dao_Factory::get_rp_event_detail_dao( $this->credentials->prefix )->insert( $event );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $fam_event = new RP_Fam_Event();
            $fam_event->fam_id = $family->id;
            $fam_event->fam_batch_id = $this->batch_id;
            $fam_event->event_id = $id;try {
                RP_Dao_Factory::get_rp_fam_event_dao( $this->credentials->prefix )->insert( $fam_event );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $this->update_event_citations( $id, $this->batch_id, $p_event->citations );
        }
    }

    /**
     *
     * @param string $event_id
     * @param integer $batch_id
     * @param array $citations
     */
    function update_event_citations( $event_id, $batch_id, $citations ) {
            foreach ( $citations as $citation ) {
            $cite = new RP_Source_Cite();
            $cite->source_id = $citation->source_id;
            $cite->source_batch_id = $batch_id;
            $cite->source_page = $citation->page;
            $cite->event_type = $citation->event_type;
            $cite->event_role = $citation->role_in_event;
            $cite->quay = $citation->quay;
            //$cite->sourceDescription = $citation->;
            try {
                $id = RP_Dao_Factory::get_rp_source_cite_dao( $this->credentials->prefix )->insert( $cite );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
            $event_cite = new RP_Event_Cite();
            $event_cite->event_id = $event_id;
            $event_cite->cite_id = $id;try {
                $id = RP_Dao_Factory::get_rp_event_cite_dao( $this->credentials->prefix )->insert( $event_cite );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     *
     * @param RP_Source_Record $source
     */
    function add_src( $source ) {
        $need_update = false;
        $src = new RP_Source();
        $src->id = $source->id;
        $src->batch_id = $this->batch_id;
        $src->originator = $source->author;
        $src->source_title = $source->title;
        $src->abbr = $source->abbreviated_title;
        $src->publication_facts = $source->publication_facts;
        $src->text = $source->text;
        $src->auto_rec_id = $source->auto_rec_id;
        $src->ged_change_date = $source->change_date->date;
        try {
            $transaction = new RP_Transaction( $this->credentials );
            RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )->insert( $src );
        } catch ( Exception $e ) {
            if ( stristr( $e->getMessage(), 'Duplicate entry' ) >= 0 ) {
                $need_update = true;
            } else {
                echo $e->getMessage();
                throw $e;
            }
        }
        if ( $need_update ) {
            try {
                RP_Dao_Factory::get_rp_source_dao( $this->credentials->prefix )->update( $src );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
        $this->update_src_notes( $source );
        $transaction->commit();
    }

    /**
     *
     * @param RP_Source_Record $source
     */
    function update_src_notes( $source ) {
        RP_Dao_Factory::get_rp_source_note_dao( $this->credentials->prefix )->delete_by_src( $source->id, $this->batch_id  );
        foreach ( $source->notes as $note ) {
            $src_note = new RP_Source_Note();
            $src_note->source_id = $source->id;
            $src_note->source_batch_id = $this->batch_id;
            $src_note->note_rec_id = $note->id;
            $src_note->note = $note->text;
            try {
                $id = RP_Dao_Factory::get_rp_source_note_dao( $this->credentials->prefix )->insert( $src_note );
            } catch ( Exception $e ) {
                echo $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     *
     * @param RP_Header_Record $rec
     */
    function process_header( $rec ) {
        $this->add_hdr( $rec );
    }

    /**
     *
     * @param RP_Submission_Rec $rec
     */
    function process_submission( $rec ) {
        $this->add_subn( $rec );
    }

    /**
     *
     * @param RP_Family_Record $rec
     */
    function process_family( $rec ) {
        $this->add_fam( $rec );
    }

    /**
     *
     * @param RP_Individual_Record $rec
     */
    function process_individual( $rec ) {
        $this->add_indi( $rec );
    }

    /**
     *
     * @param RP_Media_Record $rec
     */
    function process_media( $rec ) {
    }

    /**
     *
     * @param RP_Note_Record $rec
     */
    function process_note( $rec ) {
        $this->add_note_rec( $rec );
    }

    /**
     *
     * @param RP_Repository_Record $rec
     */
    function process_repository( $rec ) {
    }

    /**
     *
     * @param RP_Source_Record $rec
     */
    function process_source( $rec ) {
        $this->add_src( $rec );
    }

    /**
     *
     * @param RP_Submitter_Record $rec
     */
    function process_submitter( $rec ) {
        $this->add_subm( $rec );
    }
}
?>
