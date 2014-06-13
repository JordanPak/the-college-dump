<?php

//////////////////////////////////////////////////////////////////
//// function to display tweets with api 1.1
//////////////////////////////////////////////////////////////////
if( !function_exists('agurghis_twitter_timeline') ):
function agurghis_twitter_timeline (
    $twitter_id,
    $max_tweets,
    $consumer_key,
    $consumer_secret,
    $user_token,
    $user_secret
) {

$transient_name = 'new_twitter_cache_' . strtolower($twitter_id);
$twitter_cache = get_transient($transient_name);

require_once( get_template_directory() . '/twitter/tmhOAuth.php' );

$tmhOAuth = new tmhOAuth(array(
        'consumer_key' => $consumer_key, //Add your Twitter Consumer Key here
        'consumer_secret' => $consumer_secret, //Add your Twitter Consumer Secret here
        'user_token' => $user_token, //Add your Twitter User Token here
        'user_secret' => $user_secret //Add your Twitter User Secret here
    ));

$twitter_data = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), array(
        'screen_name' => $twitter_id,
        'count' => $max_tweets,
        'include_rts' => true,
        'include_entities' => true
    ));

//this will store in transient
$data  = $tmhOAuth->response['response'];
$twitter_array = json_decode($data, true);

if( !$twitter_cache ) {
set_transient($transient_name, $twitter_array, 60 * 60); // 1 hour cache
}
//print_r( $twitter_cache );

/*== uncomment this and refresh to delete transient ==*/
//delete_transient($transient_name);
//delete_option($transient_name);

 $twitter = '';

        if($twitter_cache):
        foreach ( $twitter_cache as $tweet ) {
            $pubDate        = $tweet['created_at'];
            $tweet_text          = $tweet['text'];
            $tweet_permalink  = $tweet['id_str'];

            $today          = time();
            $time           = substr($pubDate, 11, 5);
            $day            = substr($pubDate, 0, 3);
            $date           = substr($pubDate, 7, 4);
            $month          = substr($pubDate, 4, 3);
            $year           = substr($pubDate, 25, 5);
            $english_suffix = date('jS', strtotime(preg_replace('/\s+/', ' ', $pubDate)));
            $full_month     = date('F', strtotime($pubDate));


            #pre-defined tags
            $default   = $full_month . $date . $year;
            $full_date = $day . $date . $month . $year;
            $ddmmyy    = $date . $month . $year;
            $mmyy      = $month . $year;
            $mmddyy    = $month . $date . $year;
            $ddmm      = $date . $month;

            #Time difference
            $timeDiff = agurghis_twitter_dateDiff($today, $pubDate, 1);

            # Turn URLs into links
            $tweet_text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\./-]*(\?\S+)?)?)?)@', '<a target="blank" title="$1" href="$1">$1</a>', $tweet_text);

            #Turn hashtags into links
             $tweet_text = preg_replace('/#([0-9a-zA-Z_-]+)/', "<a target='blank' title='$1' href=\"http://twitter.com/search?q=%23$1\">#$1</a>",  $tweet_text);

            #Turn @replies into links
             $tweet_text = preg_replace("/@([0-9a-zA-Z_-]+)/", "<a target='blank' title='$1' href=\"http://twitter.com/$1\">@$1</a>",  $tweet_text);


            $twitter .= "<li class='news-content'><div class='twitter-feed-content'><strong class='twitter-headline'><span class='twittext'>" . $tweet_text . "</span>";


				$when  = '';

                 $twitter .= '<span class="twittime"><a target="_blank" class="time" href="https://twitter.com/'. $twitter_id . '/status/'. $tweet_permalink . '">';


            $twitter .= $timeDiff . "&nbsp;ago";

            $twitter .= "</strong></div></li>"; //end of List

        //echo $twitter;

        } //end of foreach

        //store the tweets in options string
        update_option($transient_name,$twitter);

        endif;

        echo stripcslashes( get_option($transient_name) );

}
endif;


//////////////////////////////////////////////////////////////////
//// function to get twitter follower count in api 1.1
//////////////////////////////////////////////////////////////////
if( !function_exists('agurghis_twitter_count') ):

function agurghis_twitter_count(
$twitter_id,
$consumer_key,
$consumer_secret,
$user_token,
$user_secret
) {
// WordPress Transient API Caching

$transient_follower_name = 'new_twitter_cache_follower_' . strtolower($twitter_id);
$twitter_follower_cache = get_transient($transient_follower_name);

if( !$twitter_follower_cache  ) {
require_once( get_template_directory() . '/twitter/tmhOAuth.php' );

$tmhOAuth = new tmhOAuth(array(
        'consumer_key' => $consumer_key, //Add your Twitter Consumer Key here
        'consumer_secret' => $consumer_secret, //Add your Twitter Consumer Secret here
        'user_token' => $user_token, //Add your Twitter User Token here
        'user_secret' => $user_secret //Add your Twitter User Secret here
    ));

// Send the API request
$json = json_decode($tmhOAuth->request(
	'GET',
	$tmhOAuth->url('1.1/users/show.json'),
        array('screen_name' => $twitter_id )
        ));

// Extract the follower and tweet counts
$followerCount = json_decode($tmhOAuth->response['response'])->followers_count;
$tweetCount = json_decode($tmhOAuth->response['response'])->statuses_count;

$output = $followerCount;

if($output != '' || $output != '0'):
set_transient($transient_follower_name, $output, 60 * 60); //1 hour cache
update_option($transient_follower_name, $output);
endif;

}

/*== uncomment this and refresh to delete transient ==*/
//delete_transient($transient_follower_name);
//delete_option($transient_follower_name);

return number_format( get_option($transient_follower_name) );

}

endif;

//////////////////////////////////////////////////////////////////
//// function for counting date
//////////////////////////////////////////////////////////////////
if( !function_exists('agurghis_twitter_dateDiff') ):
function agurghis_twitter_dateDiff($time1, $time2, $precision = 6) {
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }
        if ($time1 > $time2) {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
        }
        $intervals = array(
            'year',
            'month',
            'day',
            'hour',
            'minute',
            'second'
        );
        $diffs     = array();
        foreach ($intervals as $interval) {
            $diffs[$interval] = 0;
            $ttime            = strtotime("+1 " . $interval, $time1);
            while ($time2 >= $ttime) {
                $time1 = $ttime;
                $diffs[$interval]++;
                $ttime = strtotime("+1 " . $interval, $time1);
            }
        }
        $count = 0;
        $times = array();
        foreach ($diffs as $interval => $value) {
            if ($count >= $precision) {
                break;
            }
            if ($value > 0) {
                if ($value != 1) {
                    $interval .= "s";
                }
                $times[] = $value . " " . $interval;
                $count++;
            }
        }
        return implode(", ", $times);
    }
endif;