<?php

class circle{
  private$collectionLinkA;
    private $collectionImageIDA;
     private$collectionNameA;
   private  $collectionLinkB;
   private  $collectionImageIDB;
     private$collectionNameB;
     private $borderRight = '';

public function __construct($collectionLinkA, $collectionImageIDA, $collectionNameA){
$this->collectionLinkA = $collectionLinkA;
$this->collectionImageIDA = $collectionImageIDA;
$this->collectionNameA = $collectionNameA;
}

public function returnHTML(){


$output =  ' <div class="col col-sm-6  soft-shadow circle-cover-object">
    <a href="'.SITE_ROOT.'/' . $this->collectionLinkA . '">

    <p class="mask-container">
          <img src="'.SITE_ROOT.'/AzureBackups/circlepics/'.$this->collectionImageIDA.'.jpg" class=" circle-cover-large" />
    </p>

    <p>
          <h4>'.$this->collectionNameA.'</h4>
    </p>
        </a>
  </div>
  ';

echo $output;
}

}



 ?>
