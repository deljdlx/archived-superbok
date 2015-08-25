TagManager={

	getTagFormURL:'module/tag/tagmanager/getForm',
	dataSourceURL:'module/tag/tagmanager/gettree',
	saveTagURL:'module/tag/tagmanager/save',
	deleteTagURL: 'module/tag/tagmanager/delete',
	moveTagURL: 'module/tag/tagmanager/move',
	getParentsOfURL: 'module/tag/tagmanager/getParents',
	renameTagURL: 'module/tag/tagmanager/rename',


	treeNodeSelector:'#tree',
	formContainerSelector:'.tagForm',
	inputSelector:'* /deep/ .attribute_value',

	currentNodeId: null,




	captionNodeSelector:'.tagTypeCaption',
	initialize:function() {

		TagManager.application=Application.getInstance();
		TagManager.module=Application.getInstance().getModule('Tag');

		TagManager.application.setMainPanelContent(TagManager.module.getView('tagManagerLayout'));

		TagManager.formContainer= $(TagManager.formContainerSelector);
		TagManager.initializeTree();



	},


	showRemoveNodeConfirmation: function(selectedNode) {



		TagManager.application.modal.showConfirmBox('Effacer le tag "'+selectedNode.text+'" ?', function() {
			this.deleteTag(selectedNode);
			var tree = $(TagManager.treeNodeSelector).jstree(true);
			tree.delete_node(selectedNode);

		}.bind(this), function() {
			TagManager.application.modal.hideConfirmBox();
		});
	},

	deleteTag: function(tag) {
		$.ajax({
			method:'post',
			url: this.deleteTagURL,
			data: {tagId:tag.id},
			success: function(data) {
				TagManager.application.modal.notification('Le tag a été supprimé');
			}
		})
	},



	showMoveNodeConfirmation: function(movedNode, destinationNode) {
		TagManager.application.modal.showConfirmBox('Déplacer "'+movedNode.text+'" vers "'+destinationNode.text+'" ?', function() {

			var tree = $(TagManager.treeNodeSelector).jstree(true);
			tree.move_node(movedNode, destinationNode);

			$.ajax({
				url: TagManager.moveTagURL,
				data: {
					tagId: movedNode.id,
					parentId: destinationNode.id,
					success: function(data) {

					}
				}
			})


			TagManager.application.modal.hide();

		}.bind(this), function() {
			TagManager.application.modal.hide();
		});
	},





	showCreateTagForm: function(parentNode) {


		var tree = $(TagManager.treeNodeSelector).jstree(true);

		tree.open_node(parentNode);


		var newNode = tree.create_node(parentNode, {
			text:"new node",
			icon :'fa fa-tag'
		});
		tree.edit(newNode);
		TagManager.application.modal.show();
	},

	initializeTreeOptions:function() {
		$.jstree.defaults.contextmenu={
			"items" : function(selectedNode) {

				console.debug(selectedNode);

				var tree = $(TagManager.treeNodeSelector).jstree(true);
				return {
					"Create": {
						"separator_before": false,
						"separator_after": false,
						"label": "Créer",
						'icon': 'fa fa-plus',
						"action": function (item) {
							TagManager.showCreateTagForm(selectedNode);
						}
					},
					"Rename": {
						"separator_before": false,
						"separator_after": false,
						"label": "Renommer",
						'icon': 'fa fa-pencil',
						"action": function (item) {
							tree.edit(selectedNode);
						}
					},
					"Remove": {
						"separator_before": false,
						"separator_after": false,
						"label": "Effacer",
						'icon': 'fa fa-minus',
						"action": function (item) {
							TagManager.showRemoveNodeConfirmation(selectedNode);
						}
					}
				};
			}
		};
	},
	getCurrentNodeId: function() {
		return TagManager.currentNodeId;
	},

	setCurrentNodeId: function(id) {
		TagManager.currentNodeId=id;
	},



	initializeTree: function() {

		$(TagManager.treeNodeSelector).jstree('destroy');


		TagManager.initializeTreeOptions();


		TagManager.tree=$(TagManager.treeNodeSelector).jstree({
			'core' : {

				'check_callback' : function(o, movedNode, destinationNode, i, extra) {
					if(extra && extra.core && extra.origin) {

						console.debug(extra);
						//var tree = $(TagManager.treeNodeSelector).jstree(true);
						//console.debug(tree.get_node(data.parent));
						TagManager.showMoveNodeConfirmation(movedNode, destinationNode);
						return false;
					}


					 if(extra && extra.dnd && extra.pos !== 'i') { return false; }

					 if(o === "move_node" || o === "copy_node") {
					 	if(this.get_node(movedNode).parent === this.get_node(destinationNode).id) { return false; }
						 return true;
					 }
					//return false;
					//prevent all modifications

					return true;
				},
				'data' : {
					"success":function(data) {
					},

					"url" : function (node) {
						return TagManager.dataSourceURL;
					},
					"dataType" : "json", // needed only if you do not supply JSON headers

					"data" : function (node) {
						return { "nodeId" : node.id };
					}
				}
			},
			"plugins" : ["contextmenu", "dnd"]
		});


		//TagManager.tree=$(TagManager.treeNodeSelector).jstree(true);
		//console.debug(TagManager.tree);

		TagManager.tree.on("select_node.jstree", function (e, data) {
			TagManager.displayNodeData(data.node);
			Application.setURLParameter('tagId', data.node.id);
		});

		TagManager.tree.on("ready.jstree", function() {

			if(Application.getURLParameter('tagId')) {
				var tree = $(TagManager.treeNodeSelector).jstree(true);
				var node=tree.get_node();

				if(!node) {
					TagManager.loadParentsOf(Application.getURLParameter('tagId'));
				}
				else {
					tree.select_node(Application.getURLParameter('tagId'));
					TagManager.displayNodeData(Application.getURLParameter('tagId'));
				}
			}
			else {
			}
		})


		TagManager.tree.on("rename_node.jstree", function(event, data) {
			TagManager.renameNode(data.node);

		});



		/*
		TagManager.tree.on("move_node.jstree", function(event, data) {

		});
		*/
	},

	renameNode: function renameNode(node) {
		$.ajax({
			url:TagManager.renameTagURL,
			data: {
				tagId: node.id,
				caption: node.text
			},
			success: function(data) {

			}
		});
	},

	loadParentsOf:function(tagId) {
		//return;
		$.ajax({
			url: this.getParentsOfURL,
			data: {
				tagId: tagId
			},
			success: function(data) {
				var parents=data.reverse();

				var tree = $(TagManager.treeNodeSelector).jstree(true);
				var node=tree.get_node(node);

				for(var i=0; i<parents.length; i++) {
					var nodeId=parents[i].id;

					tree.open_node(nodeId, function() {
						if(tree.get_node(tagId)) {
							tree.select_node(tagId);

							var treeNode=tree.get_node(tagId);

							//selection du noeud, petite temporisation pour laisser le temps l'arbre de faire ses traitements
							setTimeout(function() {
								var node=$('li.jstree-node[id='+tagId+']');
								$('.tag-tree-container').get(0).scrollTop=$(node).offset().top-200;
							}, 500)
							return true;
						}
					});
				}
			}
		})
	},





	displayNodeData: function(node) {

		if(parseInt(node)) {
			var tree = $(TagManager.treeNodeSelector).jstree(true);
			node=tree.get_node(node);
		}

		TagManager.setCurrentNodeId(node.id);
		$(TagManager.captionNodeSelector).html('Tag : '+node.text+ ' ('+node.original.type.caption+')');
		TagManager.formContainer.html('');

		$.ajax({
			url:this.getTagFormURL+'?nodeId='+node.id,
			success: function(data) {
				TagManager.displayForm(data);
			}
		})
	},



	sendFormData: function(form) {
		var valueNodes=form.querySelectorAll(TagManager.inputSelector);

		var properties={};
		for(var i=0; i<valueNodes.length; i++) {
			if(valueNodes[i].getAttribute('type')!='checkbox') {
				properties[valueNodes[i].getAttribute('name')]=valueNodes[i].value;
			}
			else {
				if(valueNodes[i].checked) {
					properties[valueNodes[i].getAttribute('name')]=valueNodes[i].value;
				}
			}

		}

		var data= {
			nodeId: TagManager.getCurrentNodeId(),
			properties: properties
		}


		console.debug(data);

		$.ajax({
			method: 'post',
			url: TagManager.saveTagURL,
			data: data,
			success: function(data) {
				console.debug(data);
			}
		})
	},

	displayForm: function(data) {

		var form=document.createElement('form');

		jQuery(form).submit(function() {
			TagManager.sendFormData(this);
			return false;
		})


		for(var name in data) {
			jQuery(form).append(data[name]);
		}




		var button=document.createElement('button');
		button.className='mdl-button mdl-js-button mdl-button--icon';
		button.innerHTML='<i class="fa fa-check"></i>';

		var buttonContainer=document.createElement('div');
		buttonContainer.className='pmd-form-container-submit';
		buttonContainer.appendChild(button)

		jQuery(form).append(buttonContainer);

		TagManager.formContainer.append(form);

		if(typeof(componentHandler)!='undefined') {
			componentHandler.upgradeElement(button);
		}
	}

};


if(typeof(Application.modules['Tag'])=='undefined') {
	Application.modules['Tag']={};
}


Application.modules['Tag']['TagManager']=TagManager;

