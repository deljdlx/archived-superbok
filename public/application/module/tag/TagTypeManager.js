TagTypeManager={
	treeNode:'#tree',
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
		TagTypeManager.editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
			lineNumbers: true
		});
	},
	initializeTree: function() {

		TagTypeManager.initializeTreeOptions();

		TagTypeManager.tree=$(TagTypeManager.treeNode).jstree({
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
						return { "nodeId" : node.id };
					}
				}
			},
			"plugins" : ["contextmenu"]
		});

		TagTypeManager.tree.on("select_node.jstree", function (e, data) {
			if(data.node.data) {
				TagTypeManager.editor.setValue(data.node.data);
			}
			else {
				TagTypeManager.editor.setValue("{\n\t"+'"attributes": []'+"\n}");
			}
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
	}







};