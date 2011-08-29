<?php
class RP_Persona_Helper {

    const EXC = 'Exc';
    const PVT = 'Pvt';
    const MBR = 'Mbr';
    const PUB = 'Pub';
    const DEF = 'Def';
    const ADMIN = 45;
    const MEMBER = 25;
    const GUEST = 15;
    const EXCLUDE = 50;
    const ADM_ONLY = 40;
    const MBRS_ONLY = 20;
    const ANYONE = 10;

    /**
     *
     * @return integer
     */
    public static function score_user() {
        $score = RP_Persona_Helper::GUEST;
        if ( is_user_logged_in() ) {
            if ( Current_user_can( 'administrator' ) ) {
                $score = RP_Persona_Helper::ADMIN;
            } else {
                $score = RP_Persona_Helper::MEMBER;
            }
        }
        return $score;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return integer
     */
    public static function score_persona( $persona, $options ) {
        $score = RP_Persona_Helper::EXCLUDE;
        if ( isset( $persona->pscore ) ) {
            $score = $persona->pscore;
        } else if ( isset( $persona->privacy )
                && ! empty( $persona->privacy )
                && $persona->privacy != RP_Persona_Helper::DEF ) {
            if ( $persona->privacy == RP_Persona_Helper::PUB ) {
                $score = RP_Persona_Helper::ANYONE;
            } else if ( $persona->privacy == RP_Persona_Helper::MBR ) {
                $score = RP_Persona_Helper::MBRS_ONLY;
            } else if ( $persona->privacy == RP_Persona_Helper::PVT ) {
                $score = RP_Persona_Helper::ADM_ONLY;
            }
        } else {
            $living_score = $options['privacy_living'];
            if ( $living_score === false ) {
                $living_score = RP_Persona_Helper::MBR;
            }
            if ( $living_score !== RP_Persona_Helper::DEF ) {
                if ( ! isset( $persona->is_living ) ) {
                    $persona->is_living = RP_Persona_Helper::is_living( $persona );
                }
                if ( $persona->is_living ) {
                    if ( $living_score == RP_Persona_Helper::PUB ) {
                        $score = RP_Persona_Helper::ANYONE;
                    } else if ( $living_score == RP_Persona_Helper::MBR ) {
                        $score = RP_Persona_Helper::MBRS_ONLY;
                    } else if ( $living_score == RP_Persona_Helper::PVT ) {
                        $score = RP_Persona_Helper::ADM_ONLY;
                    }
                }
            }
            if ( $living_score === RP_Persona_Helper::DEF
            || $persona->is_living === false ) {
                $def_score = $options['privacy_default'];
                if ( $def_score === false
                || $def_score === RP_Persona_Helper::DEF ) {
                    $score = RP_Persona_Helper::ANYONE;
                } else {
                    if ( $def_score == RP_Persona_Helper::PUB ) {
                        $score = RP_Persona_Helper::ANYONE;
                    } else if ( $def_score == RP_Persona_Helper::MBR ) {
                        $score = RP_Persona_Helper::MBRS_ONLY;
                    } else if ( $def_score == RP_Persona_Helper::PVT ) {
                        $score = RP_Persona_Helper::ADM_ONLY;
                    }
                }
            }
        }
        $persona->pscore = $score;
        return $score;
    }

    /**
     *
     * @param integer $uscore
     * @param integer $pscore
     * @return boolean
     */
    public static function is_restricted( $uscore, $pscore ) {
        return ( $uscore < $pscore );
    }

    /**
     *
     * @param RP_Persona $persona
     * @return boolean
     */
    public static function is_living( $persona ) {
        $is_living = false;
        if ( isset( $persona->is_living ) ) {
            $is_living = $persona->is_living;
        } else {
            if ( ! isset( $persona->death_date )
            || empty( $persona->death_date ) ) {
                if ( isset( $persona->birth_date )
                && ! empty( $persona->birth_date ) ) {
                    $matches = array();
                    $cnt = preg_match_all( '/.*?([1-2][0-9][0-9][0-9]).*?/US', $persona->birth_date, $matches );
                    if ( $cnt > 0 ) {
                        $birth_year = $matches[1][$cnt - 1];
                        $test_year = ( (int)$birth_year ) + 110;
                        $curr_year = Date( 'Y' );
                        if ( $test_year >= $curr_year ) {
                            $is_living = true;
                        }
                    }
                }
            }
        }
        return $is_living;
    }

    /**
     *
     * @param string $title
     * @param string $contents
     * @param array $options
     * @param integer $page
     * @return integer
     */
    public static function create_evidence_page( $title, $contents, $options, $page = '' ) {
        // Create post object
        $my_post = array();
        $my_post['post_title'] = $title;
        $my_post['post_content'] = $contents;
        $my_post['post_status'] = 'publish';
        $my_post['post_author'] = 0;
        $my_post['post_type'] = 'page';
        $my_post['ping_status'] = 'closed';
        $my_post['comment_status'] = 'closed';
        $my_post['post_parent'] = $options['parent_page'];
        $page_id = '';
        if ( empty( $page ) ) {
            $page_id = wp_insert_post( $my_post );
        } else {
            $my_post['ID'] = $page;wp_update_post( $my_post );
            $page_id = $page;
        }
        return $page_id;
    }

    /**
     *
     * @param RP_Persona $person
     * @param string $name
     * @param array $options
     * @return integer
     */
    public static function add_page( $person, $name, $options, $batch_id ) {
        // Create post object
        $my_post = array();
        $my_post['post_title'] = $name;
        $my_post['post_content'] = "[rootsPersona personId='$person' batchId='$batch_id'/]";
        $my_post['post_status'] = 'publish';
        $my_post['post_author'] = 0;
        $my_post['post_type'] = 'page';
        $my_post['ping_status'] = get_option( 'default_ping_status' );
        $my_post['post_parent'] = $options['parent_page'];
        // Insert the post into the database
        $page_id = wp_insert_post( $my_post );
        return $page_id;
    }

    /**
     *
     * @param string $input
     * @param string $plugin_url
     * @return string
     */
    public static function return_default_empty( $input, $plugin_url ) {
        $block = "<div class='truncate'><img src='" . $plugin_url
            . "rootspersona/images/boy-silhouette.gif' class='headerBox' />"
            . "<div class='headerBox'><span class='headerBox'>"
            . $input . '</span></div></div>'
            . "<br/><div class='personBanner'><br/></div>";
        return $block;
    }

    /**
     *
     * @param RP_Persona $persona
     * @return RP_Persona
     */
    public static function get_unknown( $persona = null ) {
        $p = new RP_Persona();
        $p->id = 0;
        $p->batch_id = 0;
        $p->full_name = '?';
        $p->gender = 'U';
        $p->birth_date = '';
        $p->death_date = '';
        if ( ! isset( $persona ) ) {
            $p->page = '#';
        } else {
            $p->page = isset( $persona->page ) ? $persona->page : '#';
        }
        return $p;
    }

    /**
     *
     * @param integer $src_page
     * @return string
     */
    public static function redirect_to_page( $src_page ) {
        $location = home_url('/?page_id=' . $src_page);
        // The wp_redirect command uses a PHP redirect at its core,
        // therefore, it will not work either after header information
        // has been defined for a page.
        return '<script type="text/javascript">window.location="'
                            . $location . '"; </script>';
    }

    /**
     *
     * @global WP_Query $wp_query
     * @return integer
     */
    public static function get_page_id() {
        global $wp_query;
        $curr_page_id = $wp_query->get_queried_object_id();
        return $curr_page_id;
    }

    /**
     *
     * @param array $options
     * @param string $msg
     * @return string
     */
    public static function get_banner($options, $msg) {
        $banner = '';
        if ( $options['hide_banner'] == 0 ) {
            $ban_style = '';
            if ( isset( $options['banner_bcolor'] )
                    && !empty( $options['banner_bcolor'] ) ) {
                if  ( isset( $options['banner_fcolor'] )
                        && !empty( $options['banner_fcolor'] ) ) {
                    $ban_style = ' style="color:'. $options['banner_fcolor']
                    .';background-image:none;background-color:'
                    . $options['banner_bcolor'] . ';" ';
                } else {
                    $ban_style = ' style="background-image:none;background-color:'
                    . $options['banner_bcolor'] . ';" ';
                }
            } else if ( isset( $options['banner_image'] )
                    && !empty( $options['banner_image'] ) ) {
                 if  ( isset( $options['banner_fcolor'] )
                         && !empty( $options['banner_fcolor'] ) ) {
                    $ban_style = ' style="color:'. $options['banner_fcolor']
                     .';background-image:url(\'' . $options['banner_image']
                     . '\');" ';
                } else {
                    $ban_style = ' style="background-image:none;background-color:'
                    . $options['banner_bcolor'] . ';" ';
                }
            } elseif  ( isset( $options['banner_fcolor'] )
                    && !empty( $options['banner_fcolor'] ) ) {
                $ban_style = ' style="color:' . $options['banner_fcolor'] . ';" ';
            }

            $banner = '<div class="rp_banner"' . $ban_style . '>'
            . $msg . '</div>';
        }
        return $banner;
    }

    /**
     *
     * @param integer $curr_page
     * @param integer $per_page
     * @param integer $row_cnt
     * @param integer $targetpage
     * @return string
     */
    public static function build_pagination( $curr_page, $per_page, $row_cnt, $targetpage ) {
        $maxlinks = 9; // number of index links
        $adjacent = 4; // we center current page, how many links on each side
        $prev = $curr_page - 1; //previous page is page - 1
        $next = $curr_page + 1; //next page is page + 1
        //lastpage is = total pages / items per page, rounded up.
        $lastpage = ceil( $row_cnt / $per_page );
        $start = $curr_page - $adjacent;
        while ( $start + $maxlinks > $lastpage + 1 )$start--;
        if ( $start < 1 ) {
            $start = 1;
        }
        $finish = $start + $maxlinks - 1;
        if ( $finish > $lastpage ) {
            $finish = $lastpage;
        }

        $pagination = "<div class='pagination'>";

        if ( $curr_page != 1 ){
            $pagination .= "<a href='"
            . $targetpage . "page=1'>&lt;&lt; first</a>";
        }
        else {
            $pagination .= "<span class='disabled'>&lt;&lt; first</span>";
        }

        if ( $curr_page > 1 ) {
            $pagination .= "<a href='"
                . $targetpage . "&rootsvar=$prev'>&lt; prev</a>";
        }
        else {
            $pagination .= "<span class='disabled'>&lt; prev</span>";
        }

        for ( $p = $start; $p <= $finish; $p++ ) {
            if ( $p == $curr_page ) {
                $pagination .= "<span class='current'>$p</span>";
            } else {
                $pagination .= "<a href='" . $targetpage
                . "&rootsvar=" . $p . "'>" . $p . "</a>";
            }
        }
        if ( $curr_page != $finish ) {
            $pagination .= "<a href='" . $targetpage
            . "&rootsvar=" . $next . "'>next &gt;</a>";
        }
        else {
            $pagination .= "<span class='disabled'>next &gt;</span>";
        }
        if ( $curr_page != $lastpage ) {
            $pagination .= "<a href='" . $targetpage
            . "&rootsvar=" . $lastpage . "'>last &gt;&gt;</a>";
        }
        else {
            $pagination .= "<span class='disabled'>last &gt;&gt;</span>";
        }
        $pagination .= "</div>";
        return $pagination;
    }
}
