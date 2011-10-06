<?php
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/class-RP-Persona-Helper.php' );

class RP_Persona_Factory {
    /**
     *
     * @var RP_Credentials
     */
    var $credentials;

    /**
     *
     * @param RP_Credentials $credentials
     */
    public function __construct( $credentials ) {
        $this->credentials = $credentials;
    }

    /**
     *
     * @param string $id
     * @param integer $batch_id
     * @param array $options
     * @return RP_Persona
     */
    public function get_with_options( $id, $batch_id, $options ) {
        $persona = null;
        $uscore = $options['uscore'];
        $transaction = new RP_Transaction( $this->credentials, true );
        if ( $options['hide_header'] == 0
        || $options['hide_facts'] == 0
        || $options['hide_ancestors'] == 0
        || $options['hide_descendancy'] == 0
        || $options['hide_family_c'] == 0
        || $options['hide_family_s'] == 0 ) {
            // need the real thing
            $persona = $this->get_persona( $id, $batch_id, $uscore, $options );
        } else {
            // just need a container
            $persona = new RP_Persona();
            $persona->pscore = RP_Persona_Helper::ANYONE;
        }

        $persona->picFiles = array();
        $persona->picCaps = array();
        $pscore = $persona->pscore;
        
        if ( ! RP_Persona_Helper::is_restricted( $uscore, $pscore ) ) {
            if ( $options['hide_header'] == 0 || $options['hide_bio'] == 0 ) {
                if( ( isset ( $options['header_style'] ) && $options['header_style'] == '2' )
                        || $options['hide_bio'] == 0 ) {
                    $persona->notes = RP_Dao_Factory::get_rp_indi_note_dao( $this->credentials->prefix )
                            ->query_by_indi_id($persona->id, $persona->batch_id);
                }
            }

            // do I need fact data?
            if ( $options['hide_facts'] == 0 ) {
                $persona->facts = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                        ->get_persona_events( $id, $batch_id );
                $persona->marriages = $this->get_marriages( $persona, $uscore, $options );
            }

            // do I need family data?

            if ( $options['hide_facts'] != 0
            && ( $options['hide_family_c'] == 0
            || $options['hide_family_s'] == 0
            || $options['hide_descendancy'] == 0) ) {
                $persona->marriages = $this->get_marriages( $persona, $uscore, $options );
            }

            if ( $options['hide_ancestors'] == 0
            || $options['hide_family_c'] == 0
            || $options['hide_family_s'] == 0 ) {

                $persona->ancestors = $this->get_ancestors( $persona, $options );
                if ( $options['hide_family_c'] == 0 ) {
                    $persona->siblings = $this->get_siblings( $persona, $options );
                    if ( $persona->ancestors[2]->id != '0' ) {
                        $persona->ancestors[2]->marriages = $this->get_marriages( $persona->ancestors[2], $uscore, $options );
                    }
                    if ( $persona->ancestors[3]->id != '0' ) {
                        $persona->ancestors[3]->marriages = $this->get_marriages( $persona->ancestors[3], $uscore, $options );
                    }
                }

            }

            if ( $options['hide_family_s'] == 0
                 || $options['hide_descendancy'] == 0 ) {
                //if ( $options['hide_facts'] != 0 ) {
                //    $persona->marriages = $this->get_marriages( $persona, $uscore, $options );
                //}
                $cnt = count( $persona->marriages );
                for ( $idx = 0;    $idx < $cnt; $idx++ ) {
                    $persona->marriages[$idx]['children'] =
                            $this->get_children( $persona->marriages[$idx]['fams'], $batch_id, $options );

                    if ( $options['hide_family_s'] == 0 ) {
                        if ( $persona->marriages[$idx]['spouse1']->id == $persona->id ) {
                            $persona->marriages[$idx]['spouse2']->marriages
                                    = $this->get_marriages( $persona->marriages[$idx]['spouse2'], $uscore, $options );
                        } else {
                            $persona->marriages[$idx]['spouse1']->marriages
                                    = $this->get_marriages( $persona->marriages[$idx]['spouse1'], $uscore, $options );
                        }

                        $persona->marriages[$idx]['spouse2']->f_persona
                                = $this->get_persona( $persona->marriages[$idx]['spouse2']->father, $batch_id, $uscore, $options );
                        $persona->marriages[$idx]['spouse2']->m_persona
                                = $this->get_persona( $persona->marriages[$idx]['spouse2']->mother, $batch_id, $uscore, $options );
                        $persona->marriages[$idx]['spouse1']->f_persona
                                = $this->get_persona( $persona->marriages[$idx]['spouse1']->father, $batch_id, $uscore, $options );
                        $persona->marriages[$idx]['spouse1']->m_persona
                                = $this->get_persona( $persona->marriages[$idx]['spouse1']->mother, $batch_id, $uscore, $options );
                    }
                }
            }

            if ( $options['hide_descendancy'] == 0 ) {
                // OK, let's build out what we have already, then fill in the rest
                $cnt = count( $persona->marriages );
                for ( $idx = 0;    $idx < $cnt; $idx++ ) {
                    $cnt2 = count( $persona->marriages[$idx]['children'] );
                    for ($idx2 = 0; $idx2 < $cnt2; $idx2++) {
                        $this->get_descendents($persona->marriages[$idx]['children'][$idx2],
                                                $uscore, $batch_id, $options);
                    }
                }
            }

            // do I need evidence data?
            if ( $options['hide_evidence'] == 0 ) {
                if ( ! RP_Persona_Helper::is_restricted( $uscore, $pscore ) ) {
                    $persona->sources =
                            RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                                    ->get_persona_sources( $id, $batch_id );
                }
            }

            // do I need picture data?
            for ( $idx = 1; $idx <= 7; $idx++ ) {
                $pic = 'picfile' . $idx ;
                if ( isset( $options[$pic] ) ) {
                    $persona->picFiles[$idx-1] = $options[$pic];
                    $cap = 'piccap' . $idx;
                    if ( isset( $options[$cap] ) ) {
                        $persona->picCaps[$idx-1] = $options[$cap];
                    }
                }
            }
        } else {
            $p = new RP_Persona();
            $persona = $this->privatize( $p );
        }
        $transaction->close();

        return $persona;
    }

    protected function get_descendents( $persona, $uscore, $batch_id, $options ) {
        $persona->marriages = $this->get_marriages( $persona, $uscore, $options );
        $cnt = count( $persona->marriages );
        for ( $idx = 0;    $idx < $cnt; $idx++ ) {
            $persona->marriages[$idx]['children'] =
                $this->get_children( $persona->marriages[$idx]['fams'], $batch_id, $options );
            $cnt2 = count( $persona->marriages[$idx]['children'] );
            for ($idx2 = 0; $idx2 < $cnt2; $idx2++) {
                $this->get_descendents($persona->marriages[$idx]['children'][$idx2], $uscore, $batch_id, $options);
            }
        }
    }
    /**
     *
     * @param string $id
     * @param integer $batch_id
     * @param integer $uscore
     * @return RP_Persona
     */
    protected function get_persona( $id, $batch_id, $uscore, $options ) {
        $persona = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                ->get_persona( $id, $batch_id );
        $this->check_permission( $persona, $uscore, $options );
        return $persona;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param integer $uscore
     * @return array
     */
    protected function get_marriages( $persona, $uscore, $options ) {
        $marriages = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )->get_marriages( $persona );
        $cnt = count( $marriages );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            if ( isset( $marriages[$idx]['spouse1'] )
            && $marriages[$idx]['spouse1']->id == $persona->id ) {
                $marriages[$idx]['spouse2'] = $this->get_persona( $marriages[$idx]['associated_person_id'], $persona->batch_id, $uscore, $options );
            } else {
                $marriages[$idx]['spouse1'] = $this->get_persona( $marriages[$idx]['associated_person_id'], $persona->batch_id, $uscore, $options );
            }
        }
        return $marriages;
    }

    /**
     *
     * @param string $fam_id
     * @param integer $batch_id
     * @param array $options
     * @return array
     */
    protected function get_children( $fam_id, $batch_id, $options ) {
        $uscore = $options['uscore'];
        $children = array();
        $kids = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                ->get_children( $fam_id, $batch_id );
        foreach ( $kids as $kid ) {
            $children[] = $this->get_persona( $kid, $batch_id, $uscore, $options );
        }
        return $children;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return array
     */
    protected function get_siblings( $persona, $options ) {
        $uscore = $options['uscore'];
        $siblings = array();
        $sibs = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                ->get_children( $persona->famc, $persona->batch_id, $options );
        foreach ( $sibs as $sib ) {
            if ( $sib == $persona->id ) {
                $siblings[] = $persona;
            } else {
                $p = $this->get_persona( $sib, $persona->batch_id, $uscore, $options );
                $p->marriages = $this->get_marriages( $p, $uscore, $options );
                $siblings[] = $p;
            }
        }
        if ( count( $siblings ) == 0 )$siblings[] = $persona;
        return $siblings;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param inteter $uscore
     */
    protected function check_permission( $persona, $uscore, $options ) {
        if ( $persona->id != '0' ) {
            if ( ! isset( $persona->pscore ) ) {
               RP_Persona_Helper::score_persona( $persona, $options );
            }
            if ( RP_Persona_Helper::is_restricted( $uscore, $persona->pscore ) ) {
                $this->privatize( $persona );
            }
        }
    }

    /**
     *
     * @param RP_Persona $persona
     * @return RP_Persona
     */
    protected function privatize( $persona ) {
        $persona->full_name = 'Private';
        $persona->birth_date = '';
        $persona->birth_place = '';
        $persona->death_date = '';
        $persona->death_place = '';
        $persona->gender = '';
        return $persona;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return array
     */
    public function get_ancestors( $persona, $options ) {
        $uscore = $options['uscore'];
        $ancestors = array();
        $ancestors[1] = $persona;
        if ( ! empty( $persona->father ) ) {
            $ancestors[2] = $this->get_persona( $persona->father, $persona->batch_id, $uscore, $options );
        }
        if ( ! empty( $persona->mother ) ) {
            $ancestors[3] = $this->get_persona( $persona->mother, $persona->batch_id, $uscore, $options );
        }
        if ( ! empty( $ancestors[2] ) ) {
            if ( ! empty( $ancestors[2]->father ) )$ancestors[4] =
                    $this->get_persona( $ancestors[2]->father, $persona->batch_id, $uscore, $options );
            if ( ! empty( $ancestors[2]->mother ) )$ancestors[5]
                    = $this->get_persona( $ancestors[2]->mother, $persona->batch_id, $uscore, $options );
        }
        if ( ! empty( $ancestors[3] ) ) {
            if ( ! empty( $ancestors[3]->father ) )$ancestors[6]
                    = $this->get_persona( $ancestors[3]->father, $persona->batch_id, $uscore, $options );
            if ( ! empty( $ancestors[3]->mother ) )$ancestors[7]
                    = $this->get_persona( $ancestors[3]->mother, $persona->batch_id, $uscore, $options );
        }
        for ( $idx = 1; $idx <= 7; $idx++ ) {
            if ( ! isset( $ancestors[$idx] )
            || empty( $ancestors[$idx] ) ) {
                $ancestors[$idx] = RP_Persona_Helper::get_unknown( $persona, false );
            }
            if ( empty( $ancestors[$idx]->page ) )$ancestors[$idx]->page
                    = $ancestors[1]->page;
        }
        return $ancestors;
    }

    /**
     *
     * @param string $id
     * @param integer $batch_id
     * @param array $options
     * @return RP_Persona
     */
    public function get_for_edit( $id, $batch_id, $options ) {
        $persona = null;
        $uscore = $options['uscore'];
        $transaction = new RP_Transaction( $this->credentials, true );
        $persona = $this->get_persona( $id, $batch_id, $uscore, $options );
        $transaction->close();

        $persona->picFiles = array();
        $persona->picCaps = array();
        for ( $idx = 1; $idx <= 7; $idx++ ) {
            $pic = 'picFile' . $idx ;
            if ( isset( $options[$pic] ) ) {
                $persona->picFiles[$idx-1] = $options[$pic];
                $cap = 'picCap' . $idx;
                if ( isset( $options[$cap] ) ) {
                    $persona->picCaps[$idx-1] = $options[$cap];
                }
            }
        }
        return $persona;
    }
}