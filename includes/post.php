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
    public $postPictureID;
    public $postUserPictureID;
    public $postDescription;
    public $postLocation;
    public $postTimeStamp;
    public $comments = array();

    public function __construct ($postPictureID, $postUserPictureID, $postUserName, $numLikes, $hasUserLiked, $numComments, $postDescription, $postLocation, $postTimeStamp){
      $this->numComments = $numComments;
      $this->numLikes = $numLikes;
      $this->hasUserLiked = $hasUserLiked;
      $this->postUserName = $postUserName;
      $this->postPictureID = $postPictureID;
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

              $canUserDelete =
                  ($_SESSION['UserName'] == $comment->commentUserName ?
                  '<button class="delete-comment-button"><i class="fa fa-times" aria-hidden="true"></i></button>' : '' );

            $currentComments .= ' <div class= "row post-comment" id="' . $comment->commentID .'">
               <div class="comment-user-picture col-md-9 col-xs-9">
                 <a href="'. SITE_ROOT .'/profile-info.php?id='. $comment->commentUserName .'" >&nbsp;
                   <img src="images/profilepics/' . $comment->commentUserPictureID . '.jpg" class="img-circle comment-picture" /> &nbsp; &nbsp; &nbsp; ' . $comment->commentUserName . '
                 </a>:
                  &nbsp;    ' . $comment->commentContent . '
               </div>
               <div col-md-3 col-xs-3">
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               '. $comment->commentTimeStamp . '
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

               ' . $canUserDelete .'

               </div>
              </div>
                <hr>';
          }

      //if numLikes = 1, then it will display the singular rather than plural
      $likeOrLikes = ($this->numLikes == 1 ? '' : 's');

      // if the user has liked, then 'liked' will be present,
      //so the default option will be for the user to unlike a post they had liked from before
      $typeOfStar = ($this->hasUserLiked ? 'liked' : '');

      $commentWithAnS =  (sizeof($this->comments) == 1 ? '' : 's');

      echo '<div class="post continer" id="' . $this->postPictureID .'">
        <div class="post-picture">
          <img src="images/panoramas/' . $this->postPictureID . '.jpg" class="panorama">
        </div>

        <div class="row ">
          <div class="container post-meta vertical-center">
            <div class="post-user-picture col-md-3 col-xs-3">
              <a href="'. SITE_ROOT .'/profile-info.php?id='. $this->postUserName .'" >&nbsp;
                <img src="images/profilepics/'.$this->postUserPictureID.'.jpg" class="img-circle profile-picture" /> &nbsp; &nbsp; &nbsp; '.$this->postUserName.'
              </a>
            </div>
            <div class="post-like-comment col-md-1 col-xs-1 " >
              <p class="lv-icons lv-top-padding ' . $typeOfStar .'"  id="' . $this->postPictureID .'">
                <button type="button" class="like-button btn-outline" href=""><i class="fa fa-star-o fa-2x "></i></button>
                <button type="button" class="unlike-button btn-outline" href=""><i class="fa fa-star fa-2x "></i></button>
                <h5>' . $this->numLikes . ' like' . $likeOrLikes . '</h5>
              </p>
            </div>
            <div class="post-like-comment col-md-2 col-xs-2 " >
              <p class="lv-icons lv-top-padding">
                <a href="" ng-click="showcomments' . $this->postPictureID . ' = !showcomments' . $this->postPictureID . '"><i class="fa fa-comment-o fa-2x " ></i>
                <h5 id="counter' . $this->postPictureID.'">' . sizeof($this->comments) . ' comment'.$commentWithAnS.'</h5>
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
            <div col-md-3 col-xs-3">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;'. $this->postTimeStamp . '
            </div>
          </div>
             <hr>
        </div>
        <div id="currentComments' .$this->postPictureID .'" class="row  animated" ng-class="\'showcomments' . $this->postPictureID . '\' ? \'slideInLeft\' : \'slideOutRight\'" ng-show="showcomments' . $this->postPictureID . '">
      ' . $currentComments . '
      </div>
      <form class="row user-comment"  id="' . $this->postPictureID .'">
      <input type="text" name="Comment" id="Comment" class="form-control actual-comment" placeholder="What do you want to say about it?"/>
         <input type="submit" name="submit" class="btn btn-default comment-button" value="comment"  />
      </form>
      <hr>
      </div>
    ';
    }

}
