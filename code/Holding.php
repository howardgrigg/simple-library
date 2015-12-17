<?php
class Holding extends DataObject
{

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
    public function Available()
    {
        return ($this->Issued == 0 && $this->Active == 1 ? true : false);
    }
    public function AvailableNice()
    {
        return ($this->Available() == true ? "Yes" : "No");
    }
    private static $summary_fields = array(
    "Barcode",
    "AvailableNice",
  );
    public function getTitle()
    {
        return $this->Barcode;
    }
    public function saveReturn()
    {
        $this->Issued = false;
        $this->write();
        $issue = Issue::get()->filter(array(
        "HoldingID" => $this->ID,
        "Returned" => false ))->first();
        if ($issue != null) {
            $issue->Returned = true;
            $issue->ReturnedDate = date("Y-m-d", strtotime("now"));
            $issue->write();
            return "Success";
        } else {
            return "No current issues found.";
        }
    }
    public function saveRenew()
    {
        $issue = Issue::get()->filter(array(
          "HoldingID" => $this->ID,
          "Returned" => false ))->first();
        if ($issue != null) {
            if ($issue->RenewCount < 2) {
                $issue->RenewCount = ++$issue->RenewCount;
                $issue->DueDate = date("Y-m-d", strtotime("+3 weeks"));
                $issue->write();
                $remaining = 2 - $issue->RenewCount;
                return "Success: " . $remaining . " renewals remain";
            } else {
                return "Failed - too many renewals";
            }
        } else {
            return "No current issues found.";
        }
    }
}
