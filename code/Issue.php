<?php
class Issue extends DataObject{
  private static $db = array(
    "DueDate"  => "Date",
    "RenewCount" =>  "Int",
    "ReturnedDate"  => "Date",
    "Returned"  =>  "Boolean"
  );
  
  private static $has_one = array(
    "Member"  => "Member",
    "Holding" =>  "Holding"
  );
  
  public function populateDefaults() {
    $this->DueDate = date("Y-m-d",strtotime("+3 weeks"));
    $this->Returned = false;
    parent::populateDefaults();
  } 
  private static $summary_fields = array(
    "Member.Name",
    "Holding.Barcode",
    "Created",
    "DueDate",
    "ReturnedDate",
    "RenewCount"
  );
  
  private static $searchable_fields = array(
    "Member.FirstName",
    "Member.Surname",
    "Created",
    "DueDate",
    "ReturnedDate",
    "Returned"
  );
  
  static $default_sort = 'Created DESC';
  
  public function getTitle() {
    return "Issue: {$this->ID}";
  }
  
  public function onBeforeWrite(){
    if($this->Returned == false){
      $this->Holding()->Issued = true;
    }else{
      $this->Holding()->Issued = false;
    }
    $this->Holding()->write();
    parent::onBeforeWrite();
  }
  
  public function saveIssue($memberID, $barcode){
    $holding = Holding::get()->filter("Barcode", $barcode)->first();
    $member = Member::get()->byID($memberID);

    if($holding != null && $member != null && $holding->Available() == true){
        $this->MemberID = $memberID;
        $this->HoldingID = $holding->ID;
        $holding->Issued = true;
        $holding->write();
        $this->write();
        return true;
      }else{
        return false;
      }
  }
  
}