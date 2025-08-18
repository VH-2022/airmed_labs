<?php

class JustDialEmail {

    private $html;
    private $name;
    private $phone;
    private $email;
    private $inquiryTime;
    private $requirement;
    private $branchInfo;
    private $city;

    function __construct($str) {
        $this->html = <<<HTML
$str
HTML;
    }

    public function getAhmGeedbackData() {
        $dom = new DOMDocument();
        $dom->loadHTML($this->html);
        $tablesList = $dom->getElementsByTagName("table")[0];
        $output = array();
        $tables = $tablesList->getElementsByTagName("table");
        $tableRows = $tables[0]->getElementsByTagName('table')[1];
        if ($tableRows == null) {
            $tableRows = $tables[1];
        }
        $rows = $tableRows->getElementsByTagName('tr');
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            if (isset($cells[1])) {
                $node = trim($cells[0]->nodeValue);
                $node = trim(preg_replace('/\s+/', ' ', $node));
                //   echo "<textarea>" . trim(preg_replace('/\s+/', ' ', $node)) . "</textarea>";
                $output[$node] = trim($cells[1]->nodeValue);
                switch ($node) {
                    case 'Caller Name:';
                        $this->name = $cells[1]->nodeValue;

                        break;
                    case 'Caller Requirement:';
                        $this->requirement = $cells[1]->nodeValue;
                        break;
                    case 'Call Date & Time:';
                        $this->inquiryTime = $cells[1]->nodeValue;

                        break;
                    case 'Branch Info:';
                        $this->branchInfo = $cells[1]->nodeValue;
                        break;
                    case 'City:';
                        $this->city = $cells[1]->nodeValue;
                        break;
                    case 'Caller Phone:';
                        $this->phone = $cells[1]->nodeValue;
                        break;
                    case 'Caller Email:';
                        $this->email = $cells[1]->nodeValue;
                        break;

                    case 'User Name:';
                        $this->name = $cells[1]->nodeValue;
                        break;
                    case 'User Requirement:';
                        $this->requirement = $cells[1]->nodeValue;
                        break;
                    case 'Search Date & Time:';
                        $this->inquiryTime = $cells[1]->nodeValue;
                        break;
                    case 'City:';
                        $this->city = $cells[1]->nodeValue;
                        break;
                    case 'User Phone:';
                        $this->phone = $cells[1]->nodeValue;
                        break;
                    case 'User Email:';
                        $this->email = $cells[1]->nodeValue;
                        break;

                    default : break;
                }
            }
        }
        return ($output);
    }

    public function getDBdetails() {
        $data_mail = array("from" => "", "name" => $this->name, "search_date_time" => $this->inquiryTime,
            "city" => $this->city, "phone" => $this->phone, "email" => $this->email, "branch_info" => $this->branchInfo, "requirement" => $this->requirement);
        return $data_mail;
    }

}

?>