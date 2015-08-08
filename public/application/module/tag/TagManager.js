TagManager={
	treeNodeSelector:'#tree',
	captionNodeSelector:'.tagTypeCaption',
	initialize:function() {
		TagTypeManager.initializeTree();
		TagTypeManager.initializeEditor();
	},

	initializeTreeOptions:function() {
		$.jstree.defaults.contextmenu={
			"items" : function($node) {
				var tree = TagTypeManager.tree.jstree(true);
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
		//CodeMirror.toTextArea(document.getElementById('codeEditor'));

		TagTypeManager.editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
			lineNumbers: true
		});
	},
	initializeTree: function() {

		TagTypeManager.initializeTreeOptions();

		console.debug(TagTypeManager.treeNodeSelector);

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
						console.debug(data)
					},

					"url" : function (node) {
						return 'action.php?action=getChildren';
					},
					"dataType" : "json", // needed only if you do not supply JSON headers

					"data" : function (node) {
						console.debug('hello');
						return { "nodeId" : node.id };
					}
				}
			},
			"plugins" : ["contextmenu"]
		});


		console.debug(TagTypeManager.tree);

		TagTypeManager.tree.on("select_node.jstree", function (e, data) {
			TagTypeManager.displayNodeData(data.node);
		});

	},

	displayNodeData: function(node) {

		$(TagTypeManager.captionNodeSelector).html('Type de tag : '+node.text);
		if(node.data) {
			TagTypeManager.editor.setValue(node.data);
		}
		else {
			TagTypeManager.editor.setValue("");
		}
	}
};

if(typeof(Application.modules['Tag'])=='undefined') {
	Application.modules['Tag']={};
}


Application.modules['Tag']['TagManager']=TagManager;