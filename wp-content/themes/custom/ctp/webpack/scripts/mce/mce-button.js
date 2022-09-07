/**
 * Add a "Add Button" element to the TinyMCE editor
 */
(function() {
	tinymce.PluginManager.add('btn', function(editor, url) {
		editor.addButton('btn', {
			title: 'Add Button',
			type: 'button',
			icon: 'icon dashicons-external',

			onclick: function( ) {
				var selected_text = editor.selection.getContent({
					format: 'text'
				});

				editor.windowManager.open({
					title: 'Button',
					body: [
						{
							type: 'textbox',
							name: 'label',
							label: 'Label',
							value: selected_text
						},
						{
							type: 'textbox',
							name: 'url',
							label: 'URL'
						},
                        {
                            type: 'listbox',
                            name: 'type',
                            label: 'Type',
                            'values': [
                                {text: 'Primary', value: 'primary'},
                                {text: 'Secondary', value: 'secondary'}
                            ]
                        },
					],
					onSubmit: function(e) {
						editor.insertContent('<a class="btn btn-' + e.data.type + '" href="' + e.data.url + '">' + e.data.label + '</a>');
					}
				});
			}
		});
	});
})();
