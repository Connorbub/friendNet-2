<?php
session_start();
$number_of_posts = 5;
$_SESSION['posts_start'] = $_SESSION['posts_start'] ? $_SESSION['posts_start'] : $number_of_posts;

function get_posts($start = 0, $number_of_posts = 5) {
	$connect = mysqli_connect("localhost","root","");
	mysqli_select_db("socialnetwork",$connect);
	$posts = array();
	$query = "SELECT id,msg FROM test_msg ORDER BY id DESC LIMIT $start,$number_of_posts";
	$result = mysqli_query($connect,$query);
	while ($row = mysqli_fetch_assoc($result)) {
		preg_match("/<p>(.*)<\/p>/",$row['msg'],$matches);
		$row['msg'] = strip_tags($matches[1]);
		$posts[] = $row;
	}
	return json_encode($posts);
}
?>

<div id='posts-container'>
	<div id='posts'>

	</div>
	<div id='load-more'>

	</div>
</div>

<style type="text/css">
#posts-container            { width:400px; border:1px solid #ccc; -webkit-border-radius:10px; -moz-border-radius:10px; }
.post                       { padding:5px 10px 5px 100px; min-height:65px; border-bottom:1px solid #ccc; background:#FFFFFF; cursor:pointer;  }
.post:hover                 { background-color:lightblue; }
a.post-title                { font-weight:bold; font-size:12px; text-decoration:none; }
a.post-title:hover          { text-decoration:underline; color:#900; }
a.post-more                 { color:#900; }
p.post-content              { font-size:10px; line-height:17px; padding-bottom:0; }
#load-more                  { background-color:#eee; color:#999; font-weight:bold; text-align:center; padding:10px 0; cursor:pointer; }
#load-more:hover            { color:#666; }
.activate                   { background:url(../img/loader.gif) 140px 9px no-repeat #eee; }
</style>
<script language="javascript">
	//when the DOM is ready
$(document).ready(function(){
    //settings on top
    var domain = 'http://localhost/friendNet/testing/loadmore_ajax.php';
    var initialPosts = <?php echo get_posts(0,$_SESSION['posts_start']); ?>;
    //function that creates posts
    var postHandler = function(postsJSON) {
        $.each(postsJSON,function(i,post) {
            //post url
            var postURL = '' + domain + post.post_name;
            var id = 'post-' + post.ID;
            //create the HTML
            $('<div></div>')
            .addClass('post')
            .attr('id',id)
            //generate the HTML
            .html('<a href="' + postURL + '" class="post-title">' + post.post_title + '</a><p class="post-content">' + post.post_content + '<br /><a href="' + postURL + '" class="post-more">Read more...</a></p>')
            .click(function() {
                window.location = postURL;
            })
            //inject into the container
            .appendTo($('#posts'))
            .hide()
            .slideDown(250,function() {
                if(i == 0) {
                    $.scrollTo($('div#' + id));
                }
            });
        }); 
    };
    //place the initial posts in the page
    postHandler(initialPosts);
    //first, take care of the "load more"
    //when someone clicks on the "load more" DIV
    var start = <?php echo $_SESSION['posts_start']; ?>;
    var desiredPosts = <?php echo $number_of_posts; ?>;
    var loadMore = $('#load-more');
    //load event / ajax
    loadMore.click(function(){
        //add the activate class and change the message
        loadMore.addClass('activate').text('Loading...');
        //begin the ajax attempt
        $.ajax({
            url: 'load-more.php',
            data: {
                'start': start,
                'desiredPosts': desiredPosts
            },
            type: 'get',
            dataType: 'json',
            cache: false,
            success: function(responseJSON) {
                //reset the message
                loadMore.text('Load More');
                //increment the current status
                start += desiredPosts;
                //add in the new posts
                postHandler(responseJSON);
            },
            //failure class
            error: function() {
                //reset the message
                loadMore.text('Oops! Try Again.');
            },
            //complete event
            complete: function() {
                //remove the spinner
                loadMore.removeClass('activate');
            }
        });
    });
});
</script>