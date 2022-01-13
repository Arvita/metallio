/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

// CKEDITOR.editorConfig = function( config ) {
// 	// Define changes to default configuration here. For example:
// 	// config.language = 'fr';
// 	// config.uiColor = '#AADC6E';
// };

CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] },
		{ name: 'styles', groups: [ 'styles' ] },
	];
	config.extraPlugins = 'preview';
	config.removePlugins = 'elementspath';
	config.removeButtons = 'Source,Save,Templates,NewPage,Preview,Print,Cut,Undo,Paste,Copy,Redo,PasteText,PasteFromWord,Form,Checkbox,Radio,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,BulletedList,NumberedList,Outdent,Indent,Blockquote,CreateDiv,BidiLtr,Language,BidiRtl,Link,Unlink,Anchor,Flash,Smiley,PageBreak,Iframe,About,Format,TextField,Textarea,ShowBlocks';
};

