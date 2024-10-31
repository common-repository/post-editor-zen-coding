/**
 * Post editor Zen coding 
 */
jQuery(document).ready(function ($) {
	var WPeZC =(function(){
		var instantiated;
		var that                     = this;
		var Better_editor            = null;
		var Better_editor_loaded     = false;
		var Better_editor_textarea_h = 0;

		function init (){
			// all singleton code goes here
			return {
				create_editor:function(){
					if (instantiated.Better_editor_loaded) return false;
					$('#content-pezc').addClass('zc-active');
					instantiated.Better_editor_textarea_h = $('#content').css('height');
					window.switchEditors.switchto(document.getElementById("content-html"));
					instantiated.Better_editor = CodeMirror.fromTextArea(document.getElementById("content"), {
						mode: "text/html", 
						lineNumbers: true, 
						profile: 'xhtml',
						onKeyEvent: function(i, e) {
			    		// Hook into crtl + F1 for help
			      			if (e.keyCode == 112 && (e.ctrlKey || e.metaKey) && !e.altKey) {
					    		e.stop();
					    		//tb_show('Post Editor Zen Coding','#TB_inline?&width=600&inlineId=zc_help');
					    		//$('#TB_window').addClass('zcHelp');
					    		//instantiated.zc_thickbox();
					    		$( "#zc_help" ).dialog({width: 600, maxHeight: 400,  height: 400, show: 'slide',hide: 'slide',title : 'Post Editor Zen Coding', buttons: [
									{
										text: 'OK',
										click: function() { $(this).dialog("close"); }
									},
								]});
					      	}
					 	}
					});
					instantiated.Better_editor_loaded = true;
					instantiated.Better_editor_OnLoad();
				},
				Better_editor_OnLoad:function(){
					setUserSetting('editor', 'html');
					setUserSetting('better_editor', 1);
					// hide all the default wp stuff
					$("#wp-content-media-buttons, #content_parent, .quicktags-toolbar").hide();
					$('#wp-content-wrap').removeClass('html-active tmce-active').addClass('pezc-active');
				},
				Better_editor_remove:function(clicked){
					if (!instantiated.Better_editor_loaded) return false;
					$('#content-pezc').removeClass('zc-active');
					setUserSetting('better_editor', 0);
					instantiated.Better_editor.toTextArea();
					$("#wp-content-media-buttons, #content_parent").show();
					instantiated.Better_editor_loaded = false;
				},
				load:function(){
					var tab = $('<a id="content-pezc" class="hide-if-no-js wp-switch-editor switch-pezc">Zen Coding</a>');
					tab.prependTo('#wp-content-editor-tools');
					tab.on('click', function () {
						if (!instantiated.Better_editor_loaded) 
							instantiated.create_editor();
					});

					$('#content-html, #content-tmce').attr('onclick', '');
					$('#content-html, #content-tmce').on('click', function (e) {
						// quick fix to make sure that the right content area gets set to display
						// visible when its tab is clicked. for some reason the html textarea gets stuck on
						// display:none when going from load->ACE->Visual->HTML
						
						var clicked = $(e.currentTarget).attr('id').split('-')[1];
						instantiated.Better_editor_remove(clicked);
						switch (clicked) {
							case 'tmce':
								$(".quicktags-toolbar").hide();
								$('#content_parent').show();
								window.switchEditors.switchto(document.getElementById("content-tmce"));
								break;
							case 'html':
								$('#content').css('height',instantiated.Better_editor_textarea_h);
								$(".quicktags-toolbar").show();
								window.switchEditors.switchto(document.getElementById("content-html"));
								window.switchEditors.switchto(document.getElementById("content-tmce"));
								window.switchEditors.switchto(document.getElementById("content-html"));
								break;
						}
					});

					if (getUserSetting('better_editor') == 1) 
						instantiated.create_editor();
				},
	        	log: function(m){
	        		console.log(m);
	        	}
			}
		}
	 
		return {
			getInstance :function(){
				if (!instantiated){
					instantiated = init();
				}
				return instantiated; 
			}
		}
	})();
    WPeZC.getInstance().load();
});