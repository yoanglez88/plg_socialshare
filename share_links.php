<?php
/**
* @package  JB Library
* @copyright Copyright (C) 2006 - 2010 Joomla Bamboo. http://www.joomlabamboo.com  All rights reserved.
* @license  GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
*/
// Resize image options: exact, portrait, landscape, auto, crop, topleft, center
// no direct access
defined('_JEXEC') or die('Restricted access');

class scShareLinks
{
	public function getNiveles($nivel, $params, $url, $title) {
		$html_nivel = trim(scShareLinks::getFacebookShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getTwitterShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getTelegramShare($nivel, $params, $url, $title));		
		$html_nivel .= trim(scShareLinks::getWhatsappShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getLinkedInShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getRedditShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getTumblrShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getPinterestShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getBloggerShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getEvernoteShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getLivejournalShare($nivel, $params, $url, $title));		
		$html_nivel .= trim(scShareLinks::getPocketShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getFlipboardShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getDiasporaShare($nivel, $params, $url, $title));
		$html_nivel .= trim(scShareLinks::getInstapaperShare($nivel, $params, $url, $title));		

		return $html_nivel;
	}
	
	private function getFacebookShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_facebook", 1) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-facebook" 
							target="_blank" 
							title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Facebook") . '"
							href="http://www.facebook.com/share.php?u='. $url .'&hashtag=%23'. $params->get('facebook_hashtags', '') .'&quote='. $params->get('facebook_frase', '') .'">
						</a>
					</div>';
		}
		return $html;		
	}
	
	private function getTwitterShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_twitter", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-twitter" 
							target="_blank" 
							title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Twitter") . '"
							href="https://twitter.com/intent/tweet?text='. $title . '&via=' . $params->get('twitter_card_author', '') .'&hashtags='. $params->get('twitter_hashtags', '') .'&url='. $url .'">
						</a>
					</div>';
		}
		return $html;		
	}
	
	private function getTelegramShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_telegram", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-telegram" 
							target="_blank" 
							title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Telegram") . '"
							href="https://t.me/share/url?url='. $url .'">
						</a>
					</div>';
		}
		return $html;		
	}

	private function getWhatsappShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_whatsapp", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-whatsapp" 
							target="_blank" 
							title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Whatsapp") . '"
							href="https://wa.me/?text='. $url . '">
						</a>
					</div>';
		}
		return $html;		
	}
	
	private function getLinkedInShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_linkedin", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-linkedin" 
							target="_blank" 
							title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "LinkedIn") . '"
							href="https://www.linkedin.com/sharing/share-offsite/?url='. $url .'">
						</a>
					</div>';
		}
		return $html;		
	}
	
	private function getRedditShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-reddit" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Reddit") . '"
						href="https://reddit.com/submit?url='. $url . '&title='. $title . '">
						</a>
					</div>';
		}
		return $html;
	}

	private function getTumblrShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-tumblr" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Tumblr") . '"
						href="https://www.tumblr.com/widgets/share/tool?canonicalUrl='. $url . '&title='. $title . '">
						</a>
					</div>';
		}
		return $html;
	}

	private function getPinterestShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-pinterest" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Pinterest") . '"
						href="http://pinterest.com/pin/create/button/?url='. $url . '">
						</a>
					</div>';
		}
		return $html;
	}

	private function getBloggerShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-blogger" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Blogger") . '"
						href="https://www.blogger.com/blog-this.g?u='. $url . '&n='. $title . '">
						</a>
					</div>';
		}
		return $html;
	}

	private function getEvernoteShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-evernote" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Evernote") . '"
						href="https://www.evernote.com/clip.action?url='. $url . '&title='. $title . '">
						</a>
					</div>';
		}
		return $html;
	}
	
	private function getLivejournalShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-livejournal" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Livejournal") . '"
						href="http://www.livejournal.com/update.bml?subject='. $title . '&event='. $url . '">
						</a>
					</div>';
		}
		return $html;
	}

	private function getPocketShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-pocket" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Pocket") . '"
						href="https://getpocket.com/edit?url='. $url . '">
						</a>
					</div>';
		}
		return $html;
	}

	private function getFlipboardShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-flipboard" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Flipboard") . '"
						href="https://share.flipboard.com/bookmarklet/popout?v=2&title='. $title . '&url='. $url . '">
						</a>
					</div>';
		}
		return $html;
	}

	private function getDiasporaShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-diaspora" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Diaspora") . '"
						href="https://share.diasporafoundation.org/?title='. $title . '&url='. $url . '">
						</a>
					</div>';
		}
		return $html;
	}
	
	private function getInstapaperShare($nivel, $params, $url, $title) {
		$html = "";
		if ($params->get("button_pinterest", 0) == $nivel || $nivel == 3) {
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$html = '<div class="socialshare-link">
						<a class="socialshare-instapaper" 
						target="_blank" 
						title="' . JText::sprintf('PLG_CONTENT_SOCIALSHARE_ICON', "Instapaper") . '"
						href="http://www.instapaper.com/edit?url='. $url . '&title='. $title . '">
						</a>
					</div>';
		}
		return $html;
	}	
}
?>