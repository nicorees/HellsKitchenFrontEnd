<script type="text/javascript">
    $.Dialog({
    	flat: true,
    	draggable: true,
        shadow: true,
        overlay: true,
        icon: '<span class="icon-blocked fg-crimson"></span>',
        title: '<span class="fg-crimson">Keine Bestellungen vorhanden</span>',
        padding: 10,
        width: 500,
        content: '<br/><br/><p class="readable-text fg-crimson">' +
        		 'Du hast noch keine Bestellung getätigt.' +
				 '</p>'
    });
</script>