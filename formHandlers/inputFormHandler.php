<!-- Author: Peter Chen -->
<?php
$hours = $workArea = $date = NULL;
$hours_msg = $workArea_msg = $date_msg = NULL;

function validateForm() {
    global $hours, $workArea, $hours_msg, $workArea_msg, $date, $date_msg;
    $valid = true;

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['hours'])) {  
        if (empty($_GET['hours']) || $_GET['hours'] < 0 || $_GET['hours'] > 24 || !is_numeric($_GET['hours'])) {
            $hours_msg = "Please enter your hours for the day as a positive number <24 with no leading zeroes (ex: 8) <br />";
            $valid = false;
        }
        else
            $hours = trim($_GET['hours']);
            
        // TODO: change to get work areas from the database
        $workAreas = array("curds", "garden", "hammocks", "kettle", "pack help", "pack honcho",
            "seed racks", "seeds", "tofu hut", "trays");
        if (empty($_GET['work-area']) || !in_array(strtolower($_GET['work-area']), $workAreas)) {
            $workArea_msg = "Please pick a work area from the list <br />";
            $valid = false;
        }
        else
            $workArea = trim($_GET['work-area']);

        date_default_timezone_set('America/New_York');
        if(empty($_GET['date'])) {
            $date_msg = "Please select the day (EDT) that you worked these hours";
            $valid = false;
        }
        else if($_GET['date'] > date("Y-m-d")) {
            $date_msg = "Please select a date on or before today (" . date("Y-m-d"). ")";
            $valid = false;
        }
        else
            $date = trim($_GET['date']);
        
        return $valid;
    }
}
$confirm = validateForm();
?>