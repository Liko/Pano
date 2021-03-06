<?php

class comment{
    public $commentID;
    public $commentUserID;
    public $commentUserName;
    public $commentUserPictureID;
    public $commentContent;
    public $commentTimeStamp;

    public function __construct ($commentID, $commentUserID, $commentUserName, $commentUserPictureID, $commentContent, $commentTimeStamp ){
        $this->commentID = $commentID;
        $this->commentUserID = $commentUserID;
        $this->commentUserName = $commentUserName;
        $this->commentUserPictureID = $commentUserPictureID;
        $this->commentContent = $commentContent;
        $this->commentTimeStamp = $commentTimeStamp;
    }
}

class post{

    public $numComments;
    public $numLikes;
    public $hasUserLiked;
    public $postUserName = "";
    public $PostID;
    public $postUserPictureID;
    public $postDescription;
    public $postLocation;
    public $postTimeStamp;
    public $comments = array();

    public function __construct ($PostID, $postUserPictureID, $postUserName, $numLikes, $hasUserLiked, $numComments, $postDescription, $postLocation, $postTimeStamp){
      $this->numComments = $numComments;
      $this->numLikes = $numLikes;
      $this->hasUserLiked = $hasUserLiked;
      $this->postUserName = $postUserName;
      $this->PostID = $PostID;
      $this->postUserPictureID = $postUserPictureID;
      $this->postDescription = $postDescription;
      $this->postLocation = $postLocation;
      $this->postTimeStamp = $postTimeStamp;
    }

    public function addComments($comments){
        foreach($comments as $comment){
            $this->comments[] = $comment;
        }
    }

    public function returnHTML(){





        $currentComments = "";
          foreach ($this->comments as $comment){

              $canUserDeleteComment =
                  ($_SESSION['UserName'] == $comment->commentUserName
                  || $_SESSION['UserID'] == 12399
                  || $_SESSION['UserID'] == 12400
                  || $_SESSION['UserID'] == 12401
                  || $_SESSION['UserID'] == 12402 ?
                  '<button class="delete-comment-button"><i class="fa fa-times" aria-hidden="true"></i></button>' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' );

            $currentComments .= ' <div class= "row post-comment" id="' . $comment->commentID .'">

                 <a href="'. SITE_ROOT .'/profile-info.php?id='. $comment->commentUserName .'" >&nbsp;
  <div class="col-md-1 col-xs-1 comment-picture-col">
                   <img src="https://apppanoblob.blob.core.windows.net/profilepics/' . $comment->commentUserPictureID . '.jpg" class="img-circle comment-picture" />
                        </div>
<div class="post-comment-content col-md-7 col-xs-7">
                   ' . $comment->commentUserName . '
                 </a>:
                 <div class="post-comment-content">
                   &nbsp;    ' . $comment->commentContent . '
                 </div>
               </div>
               <div class="col-md-3 col-xs-3 comment-timestamp">
       '. $comment->commentTimeStamp . '
     </div>
      <div class="col-md-1 col-xs-1 ">

                       ' . $canUserDeleteComment .'

      </div>

              </div>
                <hr class="comment-hr">';
          }

      //if numLikes = 1, then it will display the singular rather than plural
      $likeOrLikes = ($this->numLikes == 1 ? '' : 's');

      // if the user has liked, then 'liked' will be present,
      //so the default option will be for the user to unlike a post they had liked from before
      $typeOfStar = ($this->hasUserLiked ? 'liked' : '');

      $commentWithAnS =  (sizeof($this->comments) == 1 ? '' : 's');

      $canUserDeletePost =
          ($_SESSION['UserName'] == $this->postUserName
          || $_SESSION['UserID'] == 12399
          || $_SESSION['UserID'] == 12400
          || $_SESSION['UserID'] == 12401
          || $_SESSION['UserID'] == 12402 ?
          '<button class="delete-post-button"><i class="fa fa-times" aria-hidden="true"></i></button>' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' );


      echo '<div class="post post-container animated slideInUp" id="' . $this->PostID .'">
        <div class="post-picture">
          <img src="https://apppanoblob.blob.core.windows.net/panoramas/' . $this->PostID . '.jpg" class="panorama">
        </div>

        <div class="row ">
          <div class="container post-meta vertical-center">
            <div class="post-user-picture col-md-3 col-xs-3">
              <a href="'. SITE_ROOT .'/profile-info.php?id='. $this->postUserName .'" >&nbsp;
                <img src="https://apppanoblob.blob.core.windows.net/profilepics/'.$this->postUserPictureID.'.jpg" class="img-circle profile-picture" /> &nbsp; &nbsp; &nbsp; '.$this->postUserName.'
              </a>
            </div>
            <div class="post-like-comment col-md-1 col-xs-1 " >
              <p class="lv-icons lv-top-padding ' . $typeOfStar .'"  id="' . $this->PostID .'">
                <button type="button" class="like-button btn-outline" href=""><i class="fa fa-star-o fa-2x "></i></button>
                <button type="button" class="unlike-button btn-outline" href=""><i class="fa fa-star fa-2x "></i></button>
                <h5>' . $this->numLikes . ' like' . $likeOrLikes . '</h5>
              </p>
            </div>
            <div class="post-like-comment col-md-2 col-xs-2 " >
              <p class="lv-icons lv-top-padding">
                <a href="" onclick="return false" class="comment-toggle" ><i class="fa fa-comment-o fa-2x " ></i>
                <h5 id="counter' . $this->PostID.'">' . sizeof($this->comments) . ' comment'.$commentWithAnS.'</h5>
                </a>
              </p>
            </div>
            <div class="post-content col-md-3  col-xs-3 ">
              <div class="location lv-top-padding" >
                <p>
                  <i class="fa fa-map-marker fa-lg"></i>&nbsp;  ' . $this->postLocation . '
                </p>
              </div>
              <div class="post-description">
                <p>
                  ' . $this->postDescription . '
                </p>
              </div>
            </div>
            <div col-md-2 col-xs-2">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;'. $this->postTimeStamp . '
            </div>
            <div class="col-md-1 col-xs-1 ">

                             ' . $canUserDeletePost .'

            </div>
          </div>
             <hr class="post-hr">
        </div>
        <div class="currentComments" id="currentComments' .$this->PostID .'" class="row  animated" ng-class="\'showcomments' . $this->PostID . '\' ? \'slideInLeft\' : \'slideOutRight\'" ng-show="showcomments' . $this->PostID . '">
      ' . $currentComments . '
      </div>
      <div class="row user-comment "  id="' . $this->PostID .'">
      <input type="text" name="Comment" id="Comment" class="form-control actual-comment" autocomplete="off" role="presentation" placeholder="What do you want to say about it?"/>
         <input type="submit" name="submit" class="btn btn-default comment-button" value="comment"  />
      </div>
      </div>
    ';
    }

}
