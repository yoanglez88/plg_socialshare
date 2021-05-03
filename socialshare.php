<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework', true, true);
jimport('joomla.plugin.plugin');

require_once dirname(__FILE__).'/share_links.php'; /* for video to thumbnail processing if image source come from intro or fulltext video parameter */

class plgContentSocialShare extends JPlugin {
	
    /**
    * Load the language file on instantiation.
    *
    * @var    boolean
    * @since  3.1
    */
    protected $autoloadLanguage = true;
	/**
	 * Default lang tags
	 * @var string
	 * @access private
	 */
	private $langTag = "es_ES";
	
	/**
	 * Default lang starttag
	 * @var string
	 * @access private
	 */
	private $langStartTag = 'es';
	
	/**
	 * Component dispatch view
	 * @var string
	 * @access private
	 */
	private $componentView = null;
	
	/**
	 * Generate content
	 * @param   object      The article object.  Note $article->text is also available
	 * @param   object      The article params
	 * @param   boolean     Modules context
	 * @return  string      Returns html code or empty string.
	 */
	private function getContent(&$article, &$params, $moduleContext = false) {
		$doc = JFactory::getDocument();

		$doc->addStyleSheet(JUri::root() . "plugins/content/socialshare/style/style.css");
		$doc->addScript(JUri::root() . "plugins/content/socialshare/js/script.js");
		
		$uriInstance = JUri::getInstance();
		
		if(!$moduleContext) {
			if(!class_exists('ContentHelperRoute')) {
				include_once JPATH_SITE . '/components/com_content/helpers/route.php';
			}
			if(!isset($article->slug)) {
				$url = JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->language), false);
			} else {
				$url = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->language), false);
			}
			$root = rtrim($uriInstance->getScheme() . '://' . $uriInstance->getHost(), '/');
			$url = $root . $url;
			$title = htmlentities($article->title, ENT_QUOTES, "UTF-8");
		} else {
			$url = JUri::current();
			$title = htmlentities($doc->title, ENT_QUOTES, "UTF-8");
			$article->id = rand(1000, 10000);
		}
		
		$html_nivel1 = scShareLinks::getNiveles(1, $this->params, $url, $title);
		$html_nivel2 = scShareLinks::getNiveles(2, $this->params, $url, $title);
		$html_todos = scShareLinks::getNiveles(3, $this->params, $url, $title);
		
		$alignment = $this->params->get('alignment');
		$alignClass = ' socialshare-align-';
		
		switch($alignment){
			case 0:
				$alignClass .= 'left';
				break;
			case 1:
				$alignClass .= 'center';
				break;
			case 2:
				$alignClass .= 'right';
				break;
			default:
				$alignClass .= 'left';
		}
		// $more_social_networks = $this->params->get("more_social_networks", 1);
		$lista = '
			<div class="socialshare' . $alignClass . '">
				<div class="nivel1">' . $html_nivel1 . '</div>
				<span class="NameHighlights">
					<div class="nivel2">
						<ul id="myUL">' . 4 . '</ul>
						<input type="button" id="btClick" class="btClickHere" value="Click here" />	
					</div>
				</span>

				<div id="todasRedesSociales">
					<div id="CenterDIV">
					</div>

					<div class="divFloat">
						<div class="divFloat-header">
							<input type="button" id="btClose" class="btClose" value="Close (x)" />
							<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar red social..">
						</div>
						<div class="divFloat-body">
							<ul id="myUL">' . 5 . '</ul>
						</div>
						<div class="divFloat-footer">
						</div>
					</div>
				</div>
			</div>
		';		
		return $lista;
	}
	
	/**
	 * Add social buttons into the article
	 *
	 * Method is called by the view
	 *
	 * @param   string  The context of the content being passed to the plugin.
	 * @param   object  The content object.  Note $article->text is also available
	 * @param   object  The content params
	 * @param   int     The 'page' number
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart) {
		$app = JFactory::getApplication();
		/* @var $app JApplication */
	
		if ($app->isAdmin()) {
			return;
		}
		
		if(!$article instanceof stdClass || $context == 'com_content.categories') {
			return;
		}
	
		$doc = JFactory::getDocument();
		/* @var $doc JDocumentHtml */
		$docType = $doc->getType();
	
		// Check document type
		if (strcmp("html", $docType) != 0) {
			$article->text = str_replace('{socialshare}', '', $article->text);
			return;
		}
		// Output JS APP nel Document
		if($app->input->get('print')) {
			$article->text = str_replace('{socialshare}', '', $article->text);
			return;
		}
	
		$this->componentView = $app->input->get("view");
		$isValidContext = !!preg_match('/com_content/i', $context);
		$isModuleContext = !!preg_match('/mod_custom/i', $context);
		
		// Check if it's a mod_custom context and manage as page URL sharing
		if($isModuleContext) {
			// Get plugin contents
			$content = $this->getContent($article, $this->params, true);
			$article->text = str_replace('{socialshare}', $content, $article->text);
			return;
		}

		/** Check for selected views, which will display the buttons. **/
		/** If there is a specific set and do not match, return an empty string.**/
		if (!$isValidContext || !isset($this->params)) {
			$article->text = str_replace('{socialshare}', '', $article->text);
			return;
		}
		
		$show_in_articles = $this->params->get('show_in_articles', 1);
		if (!$show_in_articles && ($this->componentView == 'article')) {
			return "";
		}
	
		// Check for category view
		$show_in_categories = $this->params->get('show_in_categories');
		if (!$show_in_categories && ($this->componentView == 'category')) {
			return;
		}
		if (!isset($article) OR empty($article->id)) {
			return;
		}
	
		$exclude_articles = $this->params->get('exclude_articles', array());
		if (!empty($exclude_articles)) {
			$exclude_articles = explode(',', $exclude_articles);
			JArrayHelper::toInteger($exclude_articles);
		}
	
		// Exluded categories
		$excluded_categories = $this->params->get('excluded_categories', array());
		if (!empty($excluded_categories)) {
			$excluded_categories = explode(',', $excluded_categories);
			JArrayHelper::toInteger($excluded_categories);
		}
	
		// Included Articles
		$included_articles = $this->params->get('include_articles', array());
		if (!empty($included_articles)) {
			$included_articles = explode(',', $included_articles);
			JArrayHelper::toInteger($included_articles);
		}
	
		if (!in_array($article->id, $included_articles)) {
			// Check exluded places
			if (in_array($article->id, $exclude_articles) || in_array($article->catid, $excluded_categories)) {
				return "";
			}
		}
		
		// Opengraph meta, extract the first image from the article-entity/first article-entity text html
		if($article->text && ($context == 'com_content.article')) {
			$var_twitter = 'name';
			$var_facebook = 'property';
			if(!$doc->getMetaData('og:image') && !$doc->getMetaData('twitter:image')) {
				$firstImageFound = false;
				$imageDetectionType = $this->params->get('image_type', 'image_fulltext');
				
				// Get the full article image if any
				if($context == 'com_content.article' && isset($article->images)) {
					$imagesDecoded = json_decode($article->images);
					if(isset($imagesDecoded->{$imageDetectionType}) && $imagesDecoded->{$imageDetectionType}) {
						$firstImageFound = true;
						$firstImage = JUri::root(false) . ltrim($imagesDecoded->{$imageDetectionType}, '/');
						$imageIntroAlt = trim($imagesDecoded->{$imageDetectionType . '_alt'});
						$doc->setMetaData('og:image', $firstImage, $var_facebook);
						$doc->setMetaData('og:image:alt', $imageIntroAlt, $var_facebook);
						$doc->setMetaData('twitter:image', $firstImage, $var_twitter);
					}
				}
				
				// Not found an image in the fulltext image, fallback to the first article image
				if(!$firstImageFound) {
					$firstImageFound = preg_match('/(<img)([^>])*(src=["\']([^"\']+)["\'])([^>])*/i', $article->text, $matches);
					if($firstImageFound) {
						$firstImage = $matches[4];
						$firstImage = preg_match('/^http/i', $firstImage) ? $firstImage : JUri::root(false) . ltrim($firstImage, '/');
						$doc->setMetaData('og:image', $firstImage, $var_facebook);
						$doc->setMetaData('twitter:image', $firstImage, $var_twitter);
					}
				}
			}
			if(isset($article->title) && !$doc->getMetaData('og:title') && !$doc->getMetaData('twitter:title')) {
				$doc->setMetaData('og:title', $article->title, $var_facebook);
				$doc->setMetaData('twitter:title', $article->title, $var_twitter);
			}
			if(!$doc->getMetaData('og:description') && !$doc->getMetaData('twitter:description')) {
				if(!isset($article->metadesc)) {
					$article->metadesc = null;
				}
				if(true) { //if(!trim($article->metadesc)) {
					$dots = JString::strlen($article->text) > 300 ? '...' : '';
					$description = JString::substr(strip_tags($article->text), 0, 300);
					$description = str_replace(PHP_EOL, '', $description);
					$description = str_replace('{socialshare}', '', $description);
					$description .= $dots;
				} else {
					$description = trim($article->metadesc);
				}
				$doc->setMetaData('og:description', $description, $var_facebook);
				$doc->setMetaData('twitter:description', $description, $var_twitter);
			}
			if(!$doc->getMetaData('og:site_name') && !$doc->getMetaData('twitter:site')) {
				$twitterCardSite = trim($this->params->get('website_name', ''));
				if($twitterCardSite) {
					$doc->setMetaData('twitter:site', $twitterCardSite, $var_twitter);
					$doc->setMetaData('og:site_name', $twitterCardSite, $var_facebook);
				}
			}
			// Additional Facebook tags
			if(!$doc->getMetaData('og:type')) {
				$doc->setMetaData('og:type', 'article', $var_facebook);
				$doc->setMetaData('og:locale', $this->langTag, $var_facebook);
				$doc->setMetaData('og:updated_time', $article->publish_up, $var_facebook);
				$admins = trim($this->params->get('facebook_admins', ''));
				$doc->setMetaData('fb:admins', $admins, $var_facebook);
				$app_id = trim($this->params->get('facebook_appid', ''));
				$doc->setMetaData('fb:app_id', $app_id, $var_facebook);
			}
			
			// Additional Twitter cards tags
			if(!$doc->getMetaData('twitter:twitter_card_type')) {
				$cardType = $this->params->get('twitter_card_type', 'summary_large_image');
				$doc->setMetaData('twitter:card', $cardType, $var_twitter);
				$twitterCardCreator = trim($this->params->get('twitter_card_creator', ''));
				if($twitterCardCreator) {
					$doc->setMetaData('twitter:creator', $twitterCardCreator);
				}
			}
		}
	
		// Get plugin contents
		$content = $this->getContent($article, $this->params);
		$article->socialshare = $content;
		$position = $this->params->get('position');
		switch ($position) {
			case 0:
				$article->text = $article->text . $content;
				break;
			case 2:
				$article->text = $article->text . $content;
				break;
			default:
				break;
		}
		return;
	}
	
	/**
	 * Override registers Listeners to the Dispatcher
	 * It allows to stop a plugin execution based on the registered listeners
	 *
	 * @override
	 * @return  void
	 */
	public function registerListeners() {
		// Ensure compatibility excluding Joomla 4
		if(version_compare(JVERSION, '4', '>=')) {
			// Check for Joomla compatibility
			JFactory::getApplication()->enqueueMessage ("The plugin package that is installed doesn't match your actual Joomla version and is not fully compatible. The package for Joomla 3.x is currently installed but you are running Joomla 4.x, if you have just upgraded your Joomla website from the version 3.x to the version 4.x the plugin must also be upgraded accordingly.<br/>To upgrade the plugin, visit our store at <a target='_blank' href='https://storejextensions.org'>https://storejextensions.org</a>, download the package for Joomla 4.x  and install it over the current one", 'error');
			return;
		} elseif (method_exists(get_parent_class($this), 'registerListeners')) {
			parent::registerListeners();
		}
	}
	
	/**
	 * Class Constructor
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'group', 'params', 'language'
	 * (this list is not meant to be comprehensive).
	 * @since 1.5
	 */
	public function __construct(&$subject, $config = array()) {
		// Ensure compatibility excluding Joomla 4
		if(version_compare(JVERSION, '4', '>=')) {
			return false;
		}
		
		parent::__construct($subject, $config);
		$lang = JFactory::getLanguage();
		$locale = $lang->getTag();
		$this->langTag = str_replace("-", "_", $locale);
		$this->langStartTag = @array_shift(explode('-', $locale));
	}		
}
