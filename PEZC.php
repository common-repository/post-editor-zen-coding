<?php
/*
Plugin Name: Post Editor Zen Coding
Plugin URI: http://en.bainternet.info/category/plugins
Description: Post Editor Zen Coding
Version: 0.2
Author: bainternet
Author URI: http://en.bainternet.info
*/
/*  Copyright 2012 bainternet  (email : admin@bainternet.info)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('PEZC')){
	class PEZC{
		
		public $option_name = 'PEZC';
		public $options;
		public $plugin_version = 0.2;

		/**
		 * Plugin Constructor
		 */
		public function __construct(){
			global $pagenow;
			if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) {
				add_action( 'admin_print_scripts-post.php', array($this, 'add_js'),99 );
				add_action( 'admin_print_scripts-post-new.php', array($this, 'add_js'),99 );			
			}
			//plugin row links
			add_filter( 'plugin_row_meta', array($this,'_my_plugin_links'), 10, 2 );
		}

		/**
		 * getOptions get plugin options
		 * @return array of options
		 * @todo add options panel
		 */
		public function getOptions(){
			if (!isset($this->options) || !is_array($this->options) && !count($this->options) > 0)
				$this->options = get_option($this->option_name);	
			return $this->options;
		}
		
		
		/**
		 * add_js
		 * @since 0.1
		 * @author Ohad Raz <admin@bainternet.info>
		 * @return void      
		 */
		public function add_js(){
			if ( 'classic' == get_user_option( 'admin_color') )
				wp_enqueue_style ( 'jquery-ui-css', plugin_dir_url( __FILE__ ).'css/jquery-ui-classic.css' );
			else
				wp_enqueue_style ( 'jquery-ui-css', plugin_dir_url( __FILE__ ).'css/jquery-ui-fresh.css' );

			wp_enqueue_style('pezc-codemirror', plugins_url('/css/codemirror.css', __FILE__));

			$url = plugins_url( '/js/',__FILE__);
			wp_enqueue_script('jquery');
			wp_enqueue_script('query-ui-core');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script( 'pezc-codemirror', $url.'codemirror.js', array('jquery'), null, 'true' );
			wp_enqueue_script( 'pezc-codemirror-xml', $url.'xml.js', array('jquery'), null, 'true' );
			wp_enqueue_script( 'pezc-codemirror-emmet', $url.'emmet.js', array('jquery'), null, 'true' );
			wp_enqueue_script( 'pezc', $url.'pezc.js', array('jquery','jquery-ui-dialog'), '22', 'true' );
			add_action('admin_footer',array($this,'add_help'));
		}
		
		/**
		 * add_help
		 * @since 0.1
		 * @author Ohad Raz <admin@bainternet.info>
		 * @return void      
		 */
		public function add_help(){
			?>
			<style>
			.zc-active{
				background-color: #E9E9E9;
				border-color: #CCCCCC #CCCCCC #E9E9E9;
				color: #333333;
			}
			</style>
			<div id="zc_help" style="display: none;">
				<h2><?php _e('Post Editor Zen Coding','pezc'); ?></h2>
				<div class="updated below-h2">
				
				<h3 ><?php _e('Info','pezc'); ?>:</h3>
				<p>	<strong><?php _e('Post Editor Zen Coding','pezc'); ?></strong> <?php _e('is developed on top of ','pezc');?><a href="http://codemirror.net/" target="_blank">Codemirror</a>  <?php _e('and','pezc');?> <a href="https://github.com/sergeche/zen-coding" target="_blank">Zen Coding</a> 
				<?php _e('by','wpzc'); ?> Ohad Raz From <a style="color: #21759B;font-weight: bolder;" target="_blank" href="http://en.bainternet.info">Bainternet</a>. <br/><?php _e('and if you like it then make a ','pezc'); ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4MMGF5X3TM5L" target="_blank" style="color: #21759B;font-weight: bolder;"><?php _e('Donation','pezc'); ?> <img src="http://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg"></a></p>
				</div>
				<div class="updated below-h2">
				<h3><?php _e('Current features of abbreviation engine','pezc'); ?></h3>
				<ul>
					<li><?php _e('ID and CLASS attributes','pezc');?>: <code>div#page.section.main</code>. </li>
					<li><?php _e('Custom attributes','pezc');?>: <code>div[title]</code>, <code>a[title="Hello world" rel]</code>, <code>td[colspan=2]</code>. </li>
					<li><?php _e('Element multiplication','pezc');?>: <code>li*5</code> will output','pezc');?> <code>&lt;li&gt;</code> <?php _e('tag five times.','pezc');?> </li>
					<li><?php _e('Item numbering with $ character','pezc');?>: <code>li.item$*3</code> will output <code>&lt;li&gt;</code> <?php _e('tag three times, replacing $ character with item number.','pezc');?> </li>
					<li><?php _e('Multiple \'$\' characters in a row are used as zero padding, i.e.','pezc');?>: <code>li.item$$$</code> → <code>li.item001</code> </li>
					<li><?php _e('Abbreviation groups with unlimited nesting','pezc');?>: <code>div#page&gt;(div#header&gt;ul#nav&gt;li*4&gt;a)+(div#page&gt;(h1&gt;span)+p*2)+div#footer</code>. <?php _e('You can literally write a full document markup with just a single line.','pezc');?> </li>
					<li><a href="/p/zen-coding/wiki/Filters"><?php _e('Filters','pezc');?></a> <?php _e('support','pezc');?> </li>
					<li><code>div</code> <?php _e('tag name can be omitted when writing element starting from ID or CLASS','pezc');?>: <code>#content&gt;.section</code> <?php _e('is the same as','pezc');?> <code>div#content&gt;div.section</code>. </li>
					<li><?php _e('Text support','pezc');?>: <code>p&gt;{Click }+a{here}+{ to continue}</code>. </li>
				</ul>
				</div>
				<div class="updated below-h2">
				<h3><?php _e('Key Bindings','pezc'); ?>:</h3>
				<p><?php _e('If You are not using a mac then Cmd = Ctrl','pezc'); ?></p>
				<ul>
					<li><code>Cmd-E</code> or <code>Tab</code>: <?php _e('Expand abbreviation','pezc'); ?></li>
					<li><code>Cmd-D</code>: <?php _e('Balance Tag (matches opening and closing tag pair)','pezc'); ?></li>
					<li><code>Shift-Cmd-D</code>: <?php _e('Balance Tag Inward','pezc'); ?></li>
					<li><code>Shift-Cmd-A</code>: <?php _e('Wrap With Abbreviation','pezc'); ?></li>
					<li><code>Ctrl-Alt-Right</code>: <?php _e('Next Edit Point','pezc'); ?></li>
					<li><code>Ctrl-Alt-Left</code>: <?php _e('Previous Edit Point','pezc'); ?></li>
					<li><code>Cmd-L</code>: <?php _e('Select line','pezc'); ?></li>
					<li><code>Cmd-Shift-M</code>: <?php _e('Merge Lines','pezc'); ?></li>
					<li><code>Cmd-/</code>: <?php _e('Toggle Comment','pezc'); ?></li>
					<li><code>Cmd-J</code>: <?php _e('Split/Join Tag','pezc'); ?></li>
					<li><code>Cmd-K</code>: <?php _e('Remove Tag','pezc'); ?></li>
					<li><code>Shift-Cmd-Y</code>: <?php _e('Evaluate Math Expression','pezc'); ?></li>
					<li><code>Ctrl-Up</code>: <?php _e('Increment Number by 1','pezc'); ?></li>
					<li><code>Ctrl-Down</code>: <?php _e('Decrement Number by 1','pezc'); ?></li>
					<li><code>Alt-Up</code>: <?php _e('Increment Number by 0.1','pezc'); ?></li>
					<li><code>Alt-Down</code>: <?php _e('Decrement Number by 0.1','pezc'); ?></li>
					<li><code>Ctrl-Alt-Up</code>: <?php _e('Increment Number by 10','pezc'); ?></li>
					<li><code>Ctrl-Alt-Down</code>: <?php _e('Decrement Number by 10','pezc'); ?></li>
					<li><code>Cmd-.</code>: <?php _e('Select Next Item','pezc'); ?></li>
					<li><code>Cmd-,</code>: <?php _e('Select Previous Item','pezc'); ?></li>
					<li><code>Cmd-B</code>: <?php _e('Reflect CSS Value','pezc'); ?></li>
				</ul>
			</div></div>
			<?php
		}
		
		/**
		 * _my_plugin_links
		 * @since 0.1
		 * @author Ohad Raz <admin@bainternet.info>
		 * @param  array $links 
		 * @param  File $file  
		 * @return array      
		 */
		public function _my_plugin_links($links, $file) {
		    $plugin = plugin_basename(__FILE__); 
		    if ($file == $plugin) // only for this plugin
		            return array_merge( $links,
		        array( '<a href="http://en.bainternet.info/category/plugins">' . __('Other Plugins by this author' ) . '</a>' ),
		        array( '<a href="http://wordpress.org/support/plugin/post-editor-zen-coding">' . __('Plugin Support') . '</a>' ),
		        array( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4MMGF5X3TM5L" target="_blank">' . __('Donate') . '</a>' )
		    );
		    return $links;
		}

	}
}
if ( is_admin() )
	new PEZC();