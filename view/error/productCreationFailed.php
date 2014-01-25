<script type="text/javascript">
    $.Dialog({
    	flat: true,
    	draggable: true,
        shadow: true,
        overlay: true,
        icon: '<span class="icon-blocked fg-crimson"></span>',
        title: '<span class="fg-crimson">Pizza Erstellung schlug fehl!</span>',
        padding: 10,
        width: 500,
        content: '<br/><p class="readable-text fg-crimson">' +
        		 'Deine Pizza konnte leider nicht erstellt werden.' +
				 '</p>' +
                 '<p class="readable-text fg-crimson">' +
                 'Bitte gib einen Namen, eine Beschreibung und Zutaten an!' +
                 '</p>'
    });
</script>