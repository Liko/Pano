<?php

//take this out when its live on the azure server
//sleep(1);

//ob_start needed to allow redirecting after login
ob_start();

//session_start() needed to use global session variabls $_SESSION etc

if(!isset($_SESSION)) {
    session_start();
}


require_once('post.php');

require_once('config.php');

require_once('dbconnect.php');

if (isset($_SERVER['HTTP_REFERER'])){
    //gets the username from the URL of the page.
    $profileUserName = substr(strchr($_SERVER['HTTP_REFERER'], 'id='), 3);
    //gets the collectionIDfrom the URL of the page.
    $CollectionID = substr(strchr($_SERVER['HTTP_REFERER'], 'CollectionID='),13 );
}

$displayRecommendations = false;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$per_page = 15;
$offset = (($page - 1) * $per_page);


//==========query to pick based on which page called loadposts.php============

// query tailored for the search bar entries starting with hashtags
if ($_SESSION['SearchTerm'][0] == '#'){

    if(!$stmt = $conn->prepare(
            "SELECT * FROM posts AS p
            LEFT JOIN tagspostsmapping as tpm on p.`PostID` = tpm.`PostID`
            LEFT JOIN tags as t on tpm.`TagID` = t.`TagID`
            LEFT JOIN user as u on u.`UserID` = p.`UserID`
            WHERE TagName = ?
            ORDER BY PostTime DESC
            LIMIT ? , ?")){
        echo "Prepare failed: (". $conn->errno .")" . $conn->error;
    }

    if(!$stmt->bind_param("sii", $_SESSION['SearchTerm'], $offset, $per_page)){
        echo "Binding parameters failed: (".$stmt->errno . ")".$stmt->error;
    }

    //query tailored for the home feed
} else if (strpos($_SERVER['HTTP_REFERER'],'home.php')){
    $displayRecommendations = true;

    if(!$stmt = $conn->prepare(
        "SELECT * FROM posts
                LEFT JOIN user ON user.`UserID` = posts.`UserID`
                WHERE user.`UserID` = ?
                OR user.`UserID` IN
                    (SELECT FriendID
                    FROM friends
                    WHERE UserID = ?)
                    ORDER BY PostTime DESC
                    LIMIT ? , ?")){
        echo "Prepare failed: (". $conn->errno .")" . $conn->error;
    }

    if(!$stmt->bind_param("iiii", $_SESSION['UserID'], $_SESSION['UserID'], $offset, $per_page)){
        echo "Binding parameters failed: (".$stmt->errno . ")".$stmt->error;
    }


//query tailored for the profile-info page
} else if (strpos($_SERVER['HTTP_REFERER'],'profile-info.php')){

    if(!$stmt = $conn->prepare(
        "SELECT * FROM posts
                    LEFT JOIN user ON user.`UserID` = posts.`UserID`
                    WHERE user.`UserName` = ?
                    ORDER BY PostTime DESC
                    LIMIT ? , ?")){
        echo "Prepare failed: (". $conn->errno .")" . $conn->error;
    }

    if(!$stmt->bind_param("sii", $profileUserName, $offset, $per_page)){
        echo "Binding parameters failed: (".$stmt->errno . ")".$stmt->error;
    }
    //query tailored for a collection
} else if (strpos($_SERVER['HTTP_REFERER'],'profile-collection.php')){

    if(!$stmt = $conn->prepare(
        "SELECT * FROM posts
                    LEFT JOIN photocollectionsmapping
                      ON posts.`PostID` = photocollectionsmapping.`PostID`
                    JOIN user
                      ON user.`UserID` = posts.`UserID`
                    WHERE photocollectionsmapping.`CollectionID` = ?
                    ORDER BY PostTime DESC
                    LIMIT ? , ?")){
        echo "Prepare failed: (". $conn->errno .")" . $conn->error;
    }

    if(!$stmt->bind_param("iii", $CollectionID, $offset, $per_page)){
        echo "Binding parameters failed: (".$stmt->errno . ")".$stmt->error;
    }
}

$posts = findPosts($page, $stmt, $conn, $displayRecommendations, $offset , $per_page);

function addRecommendedFriendsRow($conn) {
    require_once('dbconnect.php');
    include('friendrecommendation.php');
}


function findPosts($page, $stmt, $conn, $displayRecommendations, $offset , $per_page) {

    //display recommendations only when on home
    if ($page == 2 && $displayRecommendations){
        addRecommendedFriendsRow($conn);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $total_posts = mysqli_num_rows($result);

        //if there are less posts than posts per page, set $per_page to how many are remaining
        if ($total_posts< $per_page) {
            $per_page = $total_posts;
        }

        for ($i=0; $i < $per_page; $i++) {

            $post = mysqli_fetch_array($result);


            $postID = $post['PostID'];
            $numComments = 0; //calculated in query 2
            $numLikes = 0; //calculated in query 3
            $hasUserLiked = false;
            $postUserName = $post['UserName'];
            $postUserPictureID = $post['ProfilePictureID'];
            $postDescription = $post['PostText'];
            $postLocation = $post['PostLocation'];
            $postTimeStamp = $post['PostTime'];
            //arrays store all necessary comments and likes data from the second and third query
            $comments = [];
            $likes = [];

            //gathers all the comments data for a photo
            $query2 = "SELECT * FROM comments
                        LEFT JOIN user ON user.`UserID` = comments.`UserID`
                        WHERE PostID = '$postID'
                        ORDER BY CommentTime ASC";

            if ($result2 = mysqli_query($conn, $query2)) {
                $numComments = mysqli_num_rows($result2);

                while ($comment = mysqli_fetch_array($result2)) {

                    $commentID = $comment['CommentID'];
                    $commentUserID = $comment['UserID'];
                    $commentUserName = $comment['UserName'];
                    $commentUserPictureID = $comment['ProfilePictureID'];
                    $commentContent = $comment['Comment'];
                    $commentTimeStamp = $comment['CommentTime'];

                    $comment = new comment($commentID, $commentUserID, $commentUserName, $commentUserPictureID, $commentContent, $commentTimeStamp);

                    $comments[] = $comment;

                }
            }

            //gathers all the like data for a photo
            $query3 = "SELECT * FROM likes
                        LEFT JOIN user ON user.`UserID` = likes.`UserID`
                        WHERE likes.`PostID` = '$postID'";

            if ($result3 = mysqli_query($conn, $query3)) {
                $numLikes = mysqli_num_rows($result3);

                while($like = mysqli_fetch_array($result3)) {
                    $LikeUserName = $like['UserName'];
                    if ($like['UserID'] == $_SESSION['UserID']) {
                        $hasUserLiked = true;
                    }
                    $likes[] = $like;
                }
            }

            $post = new post($postID, $postUserPictureID, $postUserName , $numLikes, $hasUserLiked, $numComments, $postDescription, $postLocation, $postTimeStamp);

            echo $post->addComments($comments);
            echo $post->returnHTML();




        }
    } else {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

}

    //set the $_SESSION['SearchTerm'] variable to null, so it can prepare for the next one
    //(and so the else if statements are accessible later on)
    $_SESSION['SearchTerm'] = null;
?>
