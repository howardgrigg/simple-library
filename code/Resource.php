<?php
class Resource extends DataObject{
  private static $db = array(
    "ISBN"  => "Varchar",
    "Title" =>  "Varchar",
    "Subtitle" => "Varchar",
    "Thumbnail" => "Varchar(256)",
    "Author"  =>  "Varchar",
    "Pages" =>  "Int",
    "Publisher" => "Varchar",
    "Description" =>  "Text",
    "Type"  =>  "Enum('Book, DVD, CD','Book')"
  );
  
  private static $has_many = array(
    "Holdings" => "Holding"
  );
  
  static $googlebooksapi_key;
  
  private static $summary_fields = array(
    "Title",
    "Author",
  );
  
  private static $searchable_fields = array(
    "Holdings.Barcode"  =>  array(
      'title' => 'Barcode',
    ),
    "Title",
    "Author",
    "ISBN",
  );
  
  public function onBeforeWrite(){
    if($this->ISBN != null && $this->Title == null){
      $postURL = "https://www.googleapis.com/books/v1/volumes?q=isbn:".$this->ISBN."&key=".self::$googlebooksapi_key;
      $json = json_decode(file_get_contents($postURL), true);
      $info = $json["items"][0]["volumeInfo"];
      
      if(array_key_exists('title', $info)){
        $this->Title = $info['title'];}
      if(array_key_exists('subtitle', $info)){
        $this->Subtitle = $info['subtitle'];}
      if(array_key_exists('thumbnail', $info['imageLinks'])){
        $this->Thumbnail = $info['imageLinks']['thumbnail'];}
      if(array_key_exists('authors', $info)){
        $this->Author = $info['authors'][0];}
      if(array_key_exists('pageCount', $info)){
        $this->Pages = $info['pageCount'];}
      if(array_key_exists('publisher', $info)){
        $this->Publisher = $info['publisher'];}
      if(array_key_exists('description', $info)){
        $this->Description = $info['description'];}
      

    }
    parent::onBeforeWrite();
  }
}