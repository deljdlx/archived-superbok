TagManager={
	getTagFormURL:'module/tag/tagmanager/getForm',
	dataSourceURL:'module/tag/tagmanager/gettree',
	saveTagURL:'module/tag/tagmanager/save',


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


		//TagManager.initializeEditor();
	},

	initializeTreeOptions:function() {
		$.jstree.defaults.contextmenu={
			"items" : function($node) {
				var tree = TagManager.tree.jstree(true);
				return {
					"Create": {
						"separator_before": false,
						"separator_after": false,
						"label": "Créer",
						'icon': 'fa fa-plus',
						"action": function (obj) {
							$node = tree.create_node($node, {
								text:"caca",
								icon :'fa fa-tag'
							});
							tree.edit($node);
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
							tree.delete_node($node);
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

		TagManager.initializeTreeOptions();

		console.debug(TagManager.treeNodeSelector);

		$(TagManager.treeNodeSelector).jstree('destroy');


		TagManager.tree=$(TagManager.treeNodeSelector).jstree({
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
						return TagManager.dataSourceURL;
					},
					"dataType" : "json", // needed only if you do not supply JSON headers

					"data" : function (node) {
						return { "nodeId" : node.id };
					}
				}
			},
			"plugins" : ["contextmenu"]
		});

		TagManager.tree.on("select_node.jstree", function (e, data) {
			TagManager.displayNodeData(data.node);
		});


	},

	displayNodeData: function(node) {

		TagManager.setCurrentNodeId(node.id);

		$(TagManager.captionNodeSelector).html('Tag : '+node.text+ ' ('+node.original.type+')');

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
			properties[valueNodes[i].getAttribute('name')]=valueNodes[i].value;
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
		TagManager.formContainer.html('');


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
		buttonContainer.className='pmd form container submit';
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

