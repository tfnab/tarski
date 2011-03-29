<?php
/**
 * @package WordPress
 * @subpackage Tarski
 */

/**
 * Ties the date and time together.
 *
 * This function makes the comment date and time output more translateable.
 *
 * @since 2.0
 *
 * @return string
 *
 * @hook filter tarski_comment_datetime
 * Filters the date and time printed with a comment.
 */
function tarski_comment_datetime() {
    $datetime = sprintf(
        __('%1$s at %2$s','tarski'),
        get_comment_date(),
        get_comment_time()
    );
    return apply_filters('tarski_comment_datetime', $datetime);
}

/**
 * Returns a link to a particular comment.
 *
 * @since 2.4
 * @uses tarski_comment_datetime
 *
 * @param object $comment
 * @param mixed $args
 * @return string
 */
function tarski_comment_link($comment, $args) {
    return sprintf('<a title="%s" href="%s">%s</a>',
        __('Permalink to this comment','tarski'),
        htmlspecialchars(get_comment_link($comment->comment_ID, $args)),
        tarski_comment_datetime());
}

/**
 * Strips the http:// prefix from OpenID names.
 * 
 * @since 2.0
 * 
 * @global object $comment_author
 * @return string $comment_author
 */
function tidy_openid_names($comment_author) {
    global $comment;
    $comment_author =  str_replace('http://', '', $comment_author);
    $comment_author = rtrim($comment_author, '/');
    return $comment_author;
}

/**
 * Remove some of the cruft generated by the get_avatar function.
 * 
 * Adds proper alternate text for the image, replaces single quotes with double
 * ones for markup consistency, and removes the height and width attributes so
 * a naturally sized default image can be employed (e.g. a 1x1 pixel
 * transparent GIF so there appears to be no default image).
 * 
 * @since 2.1
 * 
 * @param string $avatar
 * @param string $id_or_email
 * @param string $size
 * @param string $default
 * @return mixed
 */
function tidy_avatars($avatar, $id_or_email, $size, $default) {
    $avatar = preg_replace("/height='[\d]+' width='[\d]+'/", '', $avatar);
    
    if (is_admin()) return $avatar;
    
    $avatar = preg_replace("/'/", '"', $avatar);
    $author_alt = sprintf(__('%s&#8217;s avatar'), get_comment_author());
    $avatar = preg_replace('/alt=""/', "alt=\"$author_alt\"", $avatar);
    
    return $avatar;
}

/**
 * Linked avatar images for Tarski.
 *
 * Links to the comment author's home page if they have one, and just returns
 * the image otherwise.
 *
 * @since 2.3
 *
 * @return string
 */
function tarski_avatar() {
    $alt    = sprintf(__('%s&#8217;s avatar'), get_comment_author());
    $avatar = get_avatar(get_comment_author_email(), '50');
    $url    = get_comment_author_url();
    
    if (empty($url) || preg_match('/^\s*http:\/\/\s*$/', $url)) {
        return $avatar;
    } else {
        return sprintf(
            '<a class="avatar-link" href="%s" rel="external nofollow">%s</a>',
            $url, $avatar);
    }
}

/**
 * Make the Tarski avatar selectable.
 * 
 * Adds the Tarski avatar to the Discussion options page, allowing it to be
 * selected but also allowing users to choose other avatars.
 * 
 * @since 2.3
 * 
 * @param array $avatar_defaults
 * @return string
 */
function tarski_default_avatar($avatar_defaults) {
    $tarski_avatar = get_template_directory_uri() . '/images/avatar.png';
    $avatar_defaults[$tarski_avatar] = 'Tarski';
    return $avatar_defaults;
}

/**
 * Returns a comment author's name, wrapped in a link if present.
 * 
 * It also includes hCard microformat markup.
 * @link http://microformats.org/wiki/hcard
 * 
 * @since 2.0
 * 
 * @return string
 * 
 * @hook filter get_comment_author_link
 * Native WordPress filter on comment author links.
 * @hook filter tarski_comment_author_link
 * Tarski-specific filter on comment author links.
 */
function tarski_comment_author_link() {
    $url    = get_comment_author_url();
    $author = get_comment_author();
    
    $htmlString = (empty($url) || 'http://' == $url)
                ? '<span class="fn">%2$s</span>'
                : '<a class="url fn" href="%1$s" rel="external nofollow">%2$s</a>';
    $authorHTML = sprintf($htmlString, $url, $author);
    
    $authorHTML = apply_filters('get_comment_author_link', $authorHTML);
    $authorHTML = apply_filters('tarski_comment_author_link', $authorHTML);
    
    return $authorHTML;
}

/**
 * Tarski-specific options to be passed to WordPress' `comment_form` function.
 *
 * @since 3.0
 *
 * @see comment_form
 *
 * @return array
 */
function tarski_comment_form() {
    $login_message  = sprintf(__('You must be %s to post a comment.', 'tarski'),
        '<a href="' . wp_login_url(get_permalink()) . '">' .
        __('logged in', 'tarski') . '</a>');
    $must_log_in    = "<p class=\"login-required\"><em>$login_message</em></p>";
    $textarea_title = __('Your comment','tarski');
    $comment_field  = <<<COMMENT_FIELD
    <div class="response textarea-wrap">
        <label for="comment">$textarea_title</label>
        <textarea name="comment" id="comment"
            cols="60" rows="10"
            aria-required="true" aria-multiline="true"></textarea>
    </div>
COMMENT_FIELD;
    
    return array(
        'comment_field'  => $comment_field,
        'must_log_in'    => $must_log_in,
        'title_reply'    => __('Reply', 'tarski'),
        'title_reply_to' => __('Reply to %s', 'tarski'));
}

?>