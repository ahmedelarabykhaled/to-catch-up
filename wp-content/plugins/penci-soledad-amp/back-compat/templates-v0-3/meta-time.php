<li class="amp-wp-posted-on">
	<time datetime="<?php echo esc_attr( date( 'c', $this->get( 'post_publish_timestamp' ) ) ); ?>">
		<?php
		echo esc_html(
			sprintf(
				_x( '%s ago', '%s = human-readable time difference', 'penci-amp' ),
				human_time_diff( $this->get( 'post_publish_timestamp' ), current_time( 'timestamp' ) )
			)
		);
		?>
	</time>
</li>
