TagTypeManager={
	dataSourceURL:'module/tag/tagtypemanager/gettree',
	updateURL:'module/tag/tagtypemanager/updateInheritableAttributes',
	getNodeInfoURL:'module/tag/tagtypemanager/getNodeInfo',
	createURL: 'module/tag/tagtypemanager/create',
	deleteURL: 'module/tag/tagtypemanager/delete',


	treeNodeSelector:'#tree',
	captionNodeSelector:'.tagTypeCaption',

	formPanelContainerSelector: '.formPanelContainer',
	formPanelContainer:null,


	defaultNewTypeName: 'Nouveau type',
	createNewNodeHandler:null,

	confirmationCheckWord: 'oui',


	initialize:function() {
		TagTypeManager.application=Application.getInstance();
		TagTypeManager.module=Application.getInstance().getModule('Tag');

		TagTypeManager.application.setMainPanelContent(TagTypeManager.module.getView('tagTypeManagerLayout'));

		TagTypeManager.formPanelContainer=document.querySelector(TagTypeManager.formPanelContainerSelector);


		TagTypeManager.initializeTree();
		TagTypeManager.initializeEditor();
	},



	renameNewNode: function(node) {
		var tree = TagTypeManager.tree.jstree(true);
		if(node.text!=TagTypeManager.defaultNewTypeName && node.text) {
			TagTypeManager.application.ajax({
				method: 'post',
				url: TagTypeManager.createURL,
				data: {
					parentId: node.parent,
					caption: node.text
				},
				success: function() {
					TagTypeManager.application.modal.notification('Modifications enregistrées');
					tree.refresh();
				}
			})
		}
		else {
			tree.delete_node(node);
		}

	},


	createNewType: function(node) {

		var newId=new Date().getTime()+'-'+Math.random()+'-'+Math.random();



		var tree = TagTypeManager.tree.jstree(true);




		var newNodeId = tree.create_node(node, {
			text: TagTypeManager.defaultNewTypeName,
			icon :'fa fa-tag',
			id:newId
		});

		tree.edit(newNodeId);
		var newNode=tree.get_node(newId);

		if(!TagTypeManager.createNewNodeHandler) {
			TagTypeManager.createNewNodeHandler=function(newNode) {
				TagTypeManager.renameNewNode(newNode);
			};


			TagTypeManager.tree.on('rename_node.jstree', function(event, data) {
				TagTypeManager.createNewNodeHandler(data.node);
			})
		}
	},

	initializeTreeOptions:function() {
		$.jstree.defaults.contextmenu={
			"items" : function($node) {
				var tree = TagTypeManager.tree.jstree(true);
				return {
					"Create": {
						"separator_before": false,
						"separator_after": false,
						"label": "Créer un sous type",
						'icon': 'fa fa-plus',
						"action": function (obj) {
							TagTypeManager.createNewType($node)

						}
					},
					"Rename": {
						"separator_before": false,
						"separator_after": false,
						"label": "Renommer",
						'icon': 'fa fa-pencil',
						"action": function (obj) {
							tree.edit($node);
						}
					},
					"Remove": {
						"separator_before": false,
						"separator_after": false,
						"label": "Effacer",
						'icon': 'fa fa-minus',
						"action": function (obj) {

							TagTypeManager.showRemoveNodeConfirmation($node);

							//tree.delete_node($node);
						}
					}
				};
			}
		};
	},

	checkRemoveNodeConfirmation:function() {
		var confirmation=jQuery('input.deleteTypeConfirmation').val();


		console.debug(TagTypeManager.confirmationCheckWord);

		if(confirmation!=TagTypeManager.confirmationCheckWord) {
			jQuery('.deleteTypeConfirmation').addClass('error');
			return false;
		}
		else {
			jQuery('.deleteTypeConfirmation').removeClass('error');
			return true
		}
	},

	showRemoveNodeConfirmation: function(selectedNode) {
		TagTypeManager.application.modal.showConfirmBox(
			'Supprimer le type "'+selectedNode.text+'" ? <div>'+
				'<form class="deleteTypeConfirmation">'+
					'<label>Tappez "'+TagTypeManager.confirmationCheckWord+'" pour confirmer <input class="deleteTypeConfirmation"/></label>'+
				'</form></div>',
			function() {

				if(TagTypeManager.checkRemoveNodeConfirmation()) {
					TagTypeManager.application.modal.hide();
					TagTypeManager.deleteNode(selectedNode)
				}

			}.bind(this), function() {

			}, function(popup) {
				document.querySelector('input.deleteTypeConfirmation').focus();
				$('form.deleteTypeConfirmation').submit(function() {
					if(TagTypeManager.checkRemoveNodeConfirmation()) {
						TagTypeManager.application.modal.hide();
						TagTypeManager.deleteNode(selectedNode);
					}
					return false;
				})
		});
	},


	deleteNode:function(selectedNode) {
		var tree = TagTypeManager.tree.jstree(true);
		tree.delete_node(selectedNode);
		TagTypeManager.application.ajax({
			'method': 'post',
			url: TagTypeManager.deleteURL,
			data: {
				'nodeId': selectedNode.id
			},
			success: function(data){
				TagTypeManager.application.modal.notification('Le type a bien été supprimé');
			}
		})


	},





	initializeEditor: function() {
		TagTypeManager.editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
			lineNumbers: true
		});
	},
	initializeTree: function() {

		TagTypeManager.initializeTreeOptions();

		$(TagTypeManager.treeNodeSelector).jstree('destroy');


		TagTypeManager.tree=$(TagTypeManager.treeNodeSelector).jstree({
			'core' : {

				'check_callback' : function(o, n, p, i, m) {
					/*
					 if(m && m.dnd && m.pos !== 'i') { return false; }
					 if(o === "move_node" || o === "copy_node") {
					 if(this.get_node(n).parent === this.get_node(p).id) { return false; }
					 }
					 */

					//prevent all modifications

					return true;
				},
				'data' : {
					"success":function(data) {
					},

					"url" : function (node) {
						return TagTypeManager.dataSourceURL;
					},
					"dataType" : "json", // needed only if you do not supply JSON headers

					"data" : function (node) {
						return { "nodeId" : node.id };
					}
				}
			},
			"plugins" : ["contextmenu", "sort"]
		});

		TagTypeManager.treeManager=$(TagTypeManager.treeNodeSelector).jstree(true);

		TagTypeManager.tree.on("select_node.jstree", function (e, data) {
			TagTypeManager.displayNodeData(data.node);
		});

		/*
		 $('#tree').on("move_node.jstree", function (e, data) {
		 console.debug(data.node.original);
		 console.debug(data.node.id);
		 console.debug(data.parent);
		 $('#tree').jstree().open_node(data.parent);
		 });

		 $(document).on('dnd_stop.vakata', function(event, data) {
		 });

		 */
	},

	sendFormData: function() {
		var nodeId=TagTypeManager.treeManager.get_selected();

		//attention nodeId est un tableau
		if(!nodeId) {
			TagTypeManager.application.modal.alert('Le noeud en cours d\'édition est introuvable');
			return;
		}

		var attributeContent=TagTypeManager.editor.getValue();


		try {
			var attributesValues=JSON.parse(attributeContent);
			TagTypeManager.application.ajax({
				method:'post',
				url: TagTypeManager.updateURL,
				data: {
					nodeId: nodeId.pop(),
					attributesVauesJSON: attributeContent,
					attributesValues:attributesValues
				},
				success: function(data) {
					TagTypeManager.application.modal.notification('Modifications enregistrées');
				}
			})


		} catch(exception) {
			TagTypeManager.application.modal.alert('Descripteur non valide, veuillez vérifier la validité du JSON '+'<div>'+exception.toString()+'</div>');
		}



	},

	displayNodeData: function(node) {


		TagTypeManager.application.ajax({
			url: TagTypeManager.getNodeInfoURL,
			data: {nodeId: node.id},
			success: function(nodeData) {

				//console.debug(data);

				$(TagTypeManager.captionNodeSelector).html('Type de tag : '+nodeData.caption);


				if(nodeData.data) {
					TagTypeManager.editor.setValue(nodeData.data);


					var button=document.createElement('button');
					button.className='mdl-button mdl-js-button mdl-button--icon';
					button.innerHTML='<i class="fa fa-check"></i>';

					$(button).click(function() {
						TagTypeManager.sendFormData();
					})



					var buttonContainer=document.createElement('div');
					buttonContainer.className='pmd-form-container-submit';
					buttonContainer.appendChild(button)

					jQuery(TagTypeManager.formPanelContainer).find('.submitContainer').html('');
					jQuery(TagTypeManager.formPanelContainer).find('.submitContainer').append(buttonContainer);

					if(typeof(componentHandler)!='undefined') {
						componentHandler.upgradeElement(button);
					}


				}
				else {
					TagTypeManager.editor.setValue("");
				}
			}

		})
	}
};

if(typeof(Application.modules['Tag'])=='undefined') {
	Application.modules['Tag']={};
}


Application.modules['Tag']['TagTypeManager']=TagTypeManager;

