function create_sortable(id_source) {


	const sortable = new Sortable.default(document.querySelectorAll(id_source+' .class_container_draggable'), {
	  draggable: id_source+' .class_draggable'
	});
	// Déclenche les éléments draggable, ici, tout élément ayant la classe "class_draggable" contenu dans un container qui a pour classe "class_container_draggable"
	// Pour créer un container qui ne peut contenir qu'un seul élément, on lui applique la classe "class_container_limitation"

	sortable.on('drag:start', (evt) => {
		$(id_source+' .class_container_limitation').each(function(i, obj) {
			// loop dans tous les container draggable
			
			capacityReached = sortable.getDraggableElementsForContainer(this).length >= 1;
			// genere un boolean si le container est utilisé
			console.log(capacityReached);
			
			this.classList.toggle('draggable_full', capacityReached);
			// Ajoute ou retire la classe draggable_full si le container est full ou non
		});
	});

	sortable.on('sortable:sort', (evt) => {
		if (evt.dragEvent.overContainer.outerHTML.includes('draggable_full')) {
			evt.cancel();
			// si la cible du drop ne contient pas la classe draggable_full alors on accepte sinon on annule l'evenement
		}
	});



}