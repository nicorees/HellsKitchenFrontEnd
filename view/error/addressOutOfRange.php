<script type="text/javascript">
    $.Dialog({
    	flat: true,
    	draggable: true,
        shadow: true,
        overlay: true,
        icon: '<span class="icon-blocked fg-crimson"></span>',
        title: '<span class="fg-crimson">Adresse zu weit entfernt!</span>',
        padding: 10,
        width: 500,
        content: '<p class="readable-text">' +
        		 	'Die Adresse, welche du angegeben hast, ist mehr als ' +
				 	'<span class="readable-text fg-crimson">' +
				 		'<?php echo Address::maxDistance(); ?> km ' +
				 	'</span>' +
					'von uns entfernt.' +
				 '</p>' +
				 '<p class="readable-text">' +
					'Bitte gib eine Adresse an, welche weniger als ' +
					'<span class="readable-text fg-crimson">' +
						'<?php echo Address::maxDistance(); ?> km ' +
					'</span>' +
					'von unserer Adresse:' +
				'</p>' +
				'<p class="readable-text">' +
					'<span class="readable-text fg-crimson">' +
						'<?php echo MAIN_ADDRESS; ?>, Deutschland ' + 
					'</span>' +
	 				'entfernt ist.' +
				'</p>'
    });
</script>