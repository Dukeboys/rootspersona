<?php

class RP_Ancestors_Panel_Creator {

    /**
     *
     * @param array $ancestors
     * @param array $options
     * @return string
     */
	public static function create( $ancestors, $options ) {
		$block = '<div class="rp_truncate">' . '<div class="rp_ancestors">' . '<table cellpadding="0" cellspacing="0" class="ancestors"><tbody>' . '<tr><td colspan="2" rowspan="6">&#160;</td>' . '<td colspan="3" rowspan="2">&#160;</td><td>&#160;</td>' . '<td rowspan="2" class="rp_nameBox">' . '<a href="' . $options['home_url'] . '?page_id=' . $ancestors[4]->page . '">' . $ancestors[4]->full_name . '</a><br/>';
		if ( ! $options['hide_dates'] ) {
			$block .= $ancestors[4]->birth_date . ' - ' . $ancestors[4]->death_date;
		}
		$block .= '</td></tr>' . '<tr><td class="rp_topleft">&#160;</td></tr>' . '<tr><td>&#160;</td>' . '<td rowspan="2" class="rp_nameBox">' . '<a href="' . $options['home_url'] . '?page_id=' . $ancestors[2]->page . '">' . $ancestors[2]->full_name . '</a><br/>';
		if ( ! $options['hide_dates'] ) {
			$block .= $ancestors[2]->birth_date . ' - ' . $ancestors[2]->death_date;
		}
		$block .= '</td><td class="rp_bottom">&#160;</td><td colspan="2" rowspan="2" class="rp_left">&#160;</td></tr>';
		$block .= '<tr><td class="rp_topleft">&#160;</td><td>&#160;</td></tr>' . '<tr><td colspan="3" rowspan="6" class="rp_left">&#160;</td><td class="rp_leftbottom">&#160;</td>' . '<td rowspan="2" class="rp_nameBox">' . '<a href="' . $options['home_url'] . '?page_id=' . $ancestors[5]->page . '">' . $ancestors[5]->full_name . '</a><br/>';
		if ( ! $options['hide_dates'] ) {
			$block .= $ancestors[5]->birth_date . ' - ' . $ancestors[5]->death_date;
		}
		$block .= '</td></tr>' . '<tr><td>&#160;</td></tr><tr><td rowspan="2" class="rp_nameBox">' . '<span style="color:blue">' . $ancestors[1]->full_name . '</span></td>' . '<td class="rp_bottom">&#160;</td><td colspan="2" rowspan="2">&#160;</td></tr>' . '<tr><td>&#160;</td></tr>';
		$block .= '<tr><td colspan="2" rowspan="6">&#160;</td><td>&#160;</td><td rowspan="2" class="rp_nameBox">' . '<a href="' . $options['home_url'] . '?page_id=' . $ancestors[6]->page . '">' . $ancestors[6]->full_name . '</a><br/>';
		if ( ! $options['hide_dates'] ) {
			$block .= $ancestors[6]->birth_date . ' - ' . $ancestors[6]->death_date;
		}
		$block .= '</td></tr><tr><td class="rp_topleft">&#160;</td></tr>' . '<tr><td class="rp_leftbottom">&#160;</td><td rowspan="2" class="rp_nameBox">' . '<a href="' . $options['home_url'] . '?page_id=' . $ancestors[3]->page . '">' . $ancestors[3]->full_name . '</a><br/>';
		if ( ! $options['hide_dates'] ) {
			$block .= $ancestors[3]->birth_date . ' - ' . $ancestors[3]->death_date;
		}
		$block .= '</td><td class="rp_bottom">&#160;</td><td colspan="2" rowspan="2" class="rp_left">&#160;</td></tr>' . '<tr><td>&#160;</td><td>&#160;</td></tr><tr><td colspan="3" rowspan="2">&#160;</td>' . '<td class="rp_leftbottom">&#160;</td><td rowspan="2" class="rp_nameBox">' . '<a href="' . $options['home_url'] . '?page_id=' . $ancestors[7]->page . '">' . $ancestors[7]->full_name . '</a><br/>';
		if ( ! $options['hide_dates'] ) {
			$block .= $ancestors[7]->birth_date . ' - ' . $ancestors[7]->death_date;
		}
		$block .= '</td></tr><tr><td>&#160;</td></tr>';
		$block .= '</tbody></table></div></div>';
		return $block;
	}
}
?>
