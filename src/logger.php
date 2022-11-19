<?php 
$username = "DB_USER";
$password = "DB_PASSWORD";
$hostname = "DB_HOST"; 
$dbName = "DB_NAME";
// Fill your own ips 
$white_list = array('59.113.11.149','29.143.30.23','127.0.0.1');
//connection to the database
$con = mysqli_connect($hostname,$username,$password,$dbName);
 
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


$log = new logger(); //this creates the class and logs the current visit
//to output the log
//$log->outputLog();
if ($log->numRecentVisits() > 5){
	if(!in_array($_SERVER['REMOTE_ADDR'],$white_list)){
    die ('Too many visits today. Please try again tomorrow or Contact Support to resolve this issue.'); //this kills the script if there have been too many visits
	}  else {
	//echo "This is your visit no:", $log->numRecentVisits()." today.";
	}
	}

class logger{

    /*
     * sql statement for creating the logging table
     * create table visitorlog
    (    visitID int(10) auto_increment primary key,
        visitIP varchar(15),
        visitURI varchar(255),
        visitReferer varchar(255),
        visitDate DATETIME,
        visitAgent varchar(255)
    )
     *
     */
    public function __construct(){
		global $con;
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->ref = empty($_SERVER['HTTP_REFERER']) ? null : $_SERVER['HTTP_REFERER'];
		$strform = "Y-m-d H:i:s";
        $this->visitDate = date($strform);
        $this->logVisit();
    }
    
    private function logVisit(){
		global $con;
        $sql = sprintf("Insert into visitorlog
                            (visitID, visitIP, visitURI, visitReferer, visitDate, visitAgent)
                            values
                            (null, '%s', '%s', '%s', '%s', '%s')", $this->ip, mysqli_real_escape_string($con, $this->uri), mysqli_real_escape_string($con,$this->ref),   $this->visitDate  , mysqli_real_escape_string($con,$this->agent));
		//echo $sql;
         mysqli_query($con, $sql);
    }
    
    public function numRecentVisits(){
		$strform = "Y-m-d H:i:s";
		global $con;
        $dayago = date($strform,strtotime('-1 day'));
		$ip = $this->ip;
        $sql = "Select count(*) as c from visitorlog where visitIP='$ip' and visitDate>'$dayago'";
        $result = mysqli_query($con, $sql);
		//echo $sql;
		$row = $result->fetch_assoc();
        //print_r($row);
		return $row['c'];
    }
    
    public function outputLog(){
		global $con;
        $sql = "Select visitDate, visitIP, visitURI, visitReferer, visitAgent from visitorlog order by visitDate desc";
        $result = mysqli_query($con,$sql);
        echo <<<HTML
<table border="1">
    <tr>
        <th>Time</th><th>IP Addr</th><th>URI</th><th>Referer</th><th>User Agent</th>
    </tr>
HTML;
        while ($row = $result->fetch_assoc()){
           // $row[0] = date("Y-m-d H:i:s", $row[0]);
            echo "<td>" . implode('</td><td>', $row) . "</td></tr>";
        }
        echo "</table>";
    }
}
?> 
