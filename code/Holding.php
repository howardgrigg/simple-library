<?php
class Holding extends DataObject{

  private static $db = array(
    "Barcode" =>  "Varchar",
    "Note"  => "Text",
    "Active" =>  "Boolean",
    "Issued" => "Boolean"
  );

  private static $has_one = array(
    "Resource" =>  "Resource"
  );
  
  private static $has_many = array(
    "Issues" => "Issue"
  );
  
  public static $defaults = array(
    "Active" => true
  );
  public function Available() {
    return ($this->Issued == 0 && $this->Active == 1 ? "Yes" : "No");
  }
  private static $summary_fields = array(
    "Barcode",
    "Available",
  );
  public function getTitle() {
    return $this->Barcode;
  }

}