<?php
class CirculationPage extends Page
{

    private static $db = array(
    );

    private static $has_one = array(
    );
}
class CirculationPage_Controller extends Page_Controller
{

    public static $allowed_actions = array(
      "IssueForm",
      "ReturnForm",
      "RenewForm"
    );
    public function IssueForm()
    {
        $f = FieldList::create();
        $f
      ->text("MemberID")
      ->text("Barcode")
      ->hidden("Member");
        $a = FieldList::create(
      FormAction::create("doIssueForm", "Issue")
        ->setStyle("success")
    );
        if (class_exists('BootstrapForm')) {
            return BootstrapForm::create($this, "IssueForm", $f, $a);
        } else {
            return Form::create($this, "IssueForm", $f, $a);
        }
    }
  
    public function doIssueForm($data, $form)
    {
        $issue = Issue::create();
        if ($issue->saveIssue($data['MemberID'], $data['Barcode'])) {
            return "success";
        } else {
            return "Error";
        }
    }
  
    public function ReturnForm()
    {
        $f = FieldList::create();
        $f->text("Barcode");
        $a = FieldList::create(
      FormAction::create("doReturnForm", "Return")
        ->setStyle("success")
    );
        if (class_exists('BootstrapForm')) {
            return BootstrapForm::create($this, "ReturnForm", $f, $a);
        } else {
            return Form::create($this, "ReturnForm", $f, $a);
        }
    }
  
    public function doReturnForm($data, $form)
    {
        if ($holding = Holding::get()->filter("Barcode", $data['Barcode'])->first()) {
            return $holding->saveReturn();
        } else {
            return "Error - barcode not recognised";
        }
    }
  
    public function RenewForm()
    {
        $f = FieldList::create();
        $f
      ->text("Barcode");
        $a = FieldList::create(
      FormAction::create("doRenewForm", "Renew")
        ->setStyle("success")
    );
        if (class_exists('BootstrapForm')) {
            return BootstrapForm::create($this, "RenewForm", $f, $a);
        } else {
            return Form::create($this, "RenewForm", $f, $a);
        }
    }
  
    public function doRenewForm($data, $form)
    {
        $holding = Holding::get()->filter("Barcode", $data['Barcode'])->first();
        return $holding->saveRenew();
    }
}
