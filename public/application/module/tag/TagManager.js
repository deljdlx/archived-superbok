TagManager={
	getTagFormURL:'module/tag/tagmanager/getForm',
	dataSourceURL:'module/tag/tagmanager/gettree',

	treeNodeSelector:'#tree',
	formContainerSelector:'.tagForm',

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
	initializeEditor: function() {
		TagManager.editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
			lineNumbers: true
		});
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

	displayNodeData: function(node) {

		$(TagManager.captionNodeSelector).html('Tag : '+node.text+ ' ('+node.original.type+')');

		$.ajax({
			url:this.getTagFormURL+'?nodeId='+node.id,
			success: function(data) {

				console.debug(data);

				TagManager.formContainer.html('');

				for(var name in data) {
					TagManager.formContainer.append(data[name]);
				}
			}
		})




	}
};


if(typeof(Application.modules['Tag'])=='undefined') {
	Application.modules['Tag']={};
}


Application.modules['Tag']['TagManager']=TagManager;

