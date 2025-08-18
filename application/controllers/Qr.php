<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Qr extends CI_Controller {

    function __construct() {
        parent::__construct();
        
    }

    public function index() {
		redirect('/user_track_report');
		
	}
	public function test(){
	     $this->load->helper("sms");
                $notification = new Sms();
                $sms_message="Patient Name:  XXXXXX(XXXXXXXX) 

CBC 
HEMOGLOBIN :-  12.8 [12.0 - 16.0] 
 Total RBC Count :-  4.97 [4.2 - 6.2] 
 H.CT :-  38.7 [26 - 50] 
 M. C. V :-  77.9 [80 - 96] 
 M.C.H. :-  25.8 [26 - 38] 
 M.C.H.C. :-  33.1 [31 - 37] 
 R.D.W :-  14.0 [11.6 - 14.6] 
 RDW-SD :-  40.3 [29 - 46] 
 PDW :-  11.9 [8.3 - 25.0] 
 MPV :-  10.5 [8.6 - 15.5] 
 P-LCR :-  27.4 [11.9 - 66.9] 
 TOTAL WBC COUNT :-  7500 [4000 - 10000] 
 Platelets Count :-  278000 [150000 - 450000] 
 Polymorphs :-  70 [40 - 70] 
 Lymphocytes :-  23 [20 - 40] 
 Monocytes :-  06 [2 - 10] 
 Eosinophils :-  01 [1 - 7] 
 Basophils :-  00 [0 - 1] 
 Smear Study-RBC :-  RBC's are Predominantly Microcytic & Normochormic. 
 Smear Study - WBC :-  WBC count is normal. 
 Smear Study-Platelets :-  Platelets are adequate 
 Smear Study - PS for MP :-  No Blood Parasites are seen. 
 
 RBS (RANDOM BLOOD GLUCOSE) 
Random Blood Sugar :-  97 [70 - 140] 
 
 LIVER FUNCTION TEST  ( LFT ) 
Total Billirubin :-  0.4 [0.0 - 2.0] 
 Direct Bilirubin :-  0.2 [0.0 - 0.2] 
 Indirect Bilirubin :- 0.2SGPT :-  12 [up to 34] 
 SGOT :-  28 [upto 31] 
 Alkaline Phosphatase :-  42 [New born(1-3 days):95-368
2months-13 yrs:115-403
14-18 yrs:58-331
Adults: 41-137] 
 Total Protein :-  6.6 [6.4 - 8.2] 
 AlBUMIN :-  3.7 [3.4 - 5] 
 GLOBULIN :-  2.9 [2.8 - 3.3] 
 Albumin Globulin Ratio :- 1.28
 RENAL FUNCTION TEST ( KIDNEY FUNCTION TEST ) 
Urea :-  15 [13 - 40] 
 Creatinine :-  0.6 [0.6 - 1.1] 
 Uric Acid :-  4.5 [3.5 - 7.2] 
 Calcium :-  8.9 [8.8 - 10.2] 
 Phosphorus :-  2.6 [2.50 - 4.50] 
 S.Sodium :-  140 [NEW BORN:133-146
ADULT:135-148] 
 Potassium :-  4.6 [NEW BORN:3.7-5.2
ADULT:3.5-5.3] 
 Chloride :-  106 [NEW BORN:96-111
ADULT:98-107] ";
                $notification->send("9601198035", $sms_message);
	    
	}
}	
?>