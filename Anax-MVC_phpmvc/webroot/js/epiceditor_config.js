var jsFileLocation = $('script[src*=epiceditor]').attr('src');  // the js file path
jsFileLocation = jsFileLocation.replace('epiceditor.min.js', '');   // the js folder path

var opts = {
	  container: 'epiceditor',
	  textarea: 'textareaEditor',
	  basePath: jsFileLocation,
	  clientSideStorage: true,
	  localStorageName: 'epiceditor',
	  useNativeFullscreen: true,
	  parser: marked,
	  file: {
		name: 'epiceditor',
		defaultContent: '',
		autoSave: false,
	  },
	  theme: {
		base: '../themes/base/epiceditor.css',
		preview: '../../../css/anax-grid/style.php',
		editor: '../themes/editor/epic-light.css'
	  },
	  button: {
		preview: true,
		fullscreen: true,
		bar: 'auto'
	  },
	  focusOnLoad: false,
	  shortcut: {
		modifier: 18,
		fullscreen: 70,
		preview: 80
	  },
	  string: {
		togglePreview: 'Toggle Preview Mode',
		toggleEdit: 'Toggle Edit Mode',
		toggleFullscreen: 'Enter Fullscreen'
	  },
	  autogrow: false
	}
	
var editor = new EpicEditor(opts).load();