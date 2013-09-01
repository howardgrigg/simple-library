<?php
class CirculationPage extends Page {

	private static $db = array(
	);

	private static $has_one = array(
	);

}
class CirculationPage_Controller extends Page_Controller {

  static $allowed_actions = array (
	  "IssueForm",
	  "ReturnForm",
	  "RenewForm"
	);

  public function IssueForm() {
    $f = FieldList::create();
    $f
      ->text("MemberID")
      ->text("Barcode")
      ->hidden("Member");
    $a = FieldList::create(
      FormAction::create("doIssueForm","Issue")
        ->setStyle("success")
    );
    if(class_exists('BootstrapForm')){
      return BootstrapForm::create($this, "IssueForm", $f, $a);
    }else{
      return Form::create($this, "IssueForm", $f, $a);
    }
  }
  
  public function doIssueForm($data, $form){
    $book = Holding::get()->filter("Barcode", $data['Barcode'])->first();
    $member = Member::get()->byID($data['MemberID']);
    
    $issue = Issue::create();
    $issue->MemberID = $member->ID;
    $issue->HoldingID = $book->ID;
    $issue->write();
    return "success";
  }
  
  public function ReturnForm() {
    $f = FieldList::create();
    $f
      ->text("Barcode");
    $a = FieldList::create(
      FormAction::create("doReturnForm","Return")
        ->setStyle("success")
    );
    if(class_exists('BootstrapForm')){
      return BootstrapForm::create($this, "ReturnForm", $f, $a);
    }else{
      return Form::create($this, "ReturnForm", $f, $a);
    }
  }
  
  public function doReturnForm($data, $form){
    $book = Holding::get()->filter("Barcode", $data['Barcode'])->first();
    $book->Issued = false;
    $issue = Issue::get()->filter(array(
        "HoldingID" => $book->ID,
        "Returned" => false ))->first();
    $issue->Returned = true;
    $issue->ReturnedDate = date("Y-m-d",strtotime("now"));
    $issue->write();
    return "success";
  }
  
  public function RenewForm() {
    $f = FieldList::create();
    $f
      ->text("Barcode");
    $a = FieldList::create(
      FormAction::create("doRenewForm","Renew")
        ->setStyle("success")
    );
    if(class_exists('BootstrapForm')){
      return BootstrapForm::create($this, "RenewForm", $f, $a);
    }else{
      return Form::create($this, "RenewForm", $f, $a);
    }
  }
  
  public function doRenewForm($data, $form){
    $book = Holding::get()->filter("Barcode", $data['Barcode'])->first();

    $issue = Issue::get()->filter(array(
        "HoldingID" => $book->ID,
        "Returned" => false ))->first();
    if($issue->RenewCount < 2){
        $issue->RenewCount = ++$issue->RenewCount;
        $issue->DueDate = date("Y-m-d",strtotime("+3 weeks"));
        $issue->write();
        return "success";
    }else{
        return "failed - too many renewals";
    }
  }

  
}