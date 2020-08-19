<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="description" content="TS_20 Add New Sensors" />
   <meta name="keywords" content="PHP" />
   <meta name="author" content="Billy Dasopatis / 101156315" />
   <title>Add New Sensor</title>
</head>
<body>


   <h1>Adding New Sensors To The Database</h1>
   <hr>
   <h4> If You dont know the format of each of these questions please reffer to the tables for guidelines</h4>
   <h4>Does your sensor spit out multiple types of data in an array? if so please fill out this form 1x for each of the senors arrays (check CanID / Sensor Name in the Sensors table to see the format)</h4>

   <?php
      require_once('databasedetails.php');
      //this function is to print out the TOP half of the table
      function printdatapresensors(){
        echo "<table border=\"2\">\n";
        echo "<tr>\n "
        ."<th scope=\"col\">CanId</th>\n "
        ."<th scope=\"col\">Sensor Name</th>\n "
        ."<th scope=\"col\">SensorTypeId</th>\n "
        ."</tr>\n ";
      }

      function printdatapresensortype(){
        echo "<table border=\"2\">\n";
        echo "<tr>\n "
        ."<th scope=\"col\">Sensor Type Id</th>\n "
        ."<th scope=\"col\">Description</th>\n "
    //    ."<th scope=\"col\">UnitID</th>\n "
        ."</tr>\n ";
      }

      function printdatapresensorunit(){
        echo "<table border=\"2\">\n";
        echo "<tr>\n "
        ."<th scope=\"col\">Unit ID</th>\n "
        ."<th scope=\"col\">Unit Name</th>\n "
        ."<th scope=\"col\">Unit Metric</th>\n "
        ."</tr>\n ";
      }

      //This function is to print out the conclusion fo the table
      function printdatapost(){
          echo "</table>\n ";
      }

      //Sanitise Input function
      function sanitise_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

      //Print Data Function
      function printdatasensors($result){
        if ($result != null)
        {
          while ($row = mysqli_fetch_assoc($result))
          {
             echo "<tr>\n ";
             echo "<td>",$row['CanId'],"</td>\n";
             echo "<td>",$row['Name'],"</td>\n";
             echo "<td>",$row['SensorTypeId'],"</td>\n";
             echo "</tr>\n ";
          }
        }
      }

      //Print Data Function
      function printdatasensortype($result){
        if ($result != null)
        {
          while ($row = mysqli_fetch_assoc($result))
          {
              echo "<tr>\n ";
             echo "<td>",$row['SensorTypeId'],"</td>\n";
             echo "<td>",$row['Description'],"</td>\n";
          //   echo "<td>",$row['UnitId'],"</td>\n";
             echo "</tr>\n ";
          }
        }
      }

      //Print Data Function
      function printdatasensorunit($result){
        if ($result != null)
        {
          while ($row = mysqli_fetch_assoc($result))
          {
            echo "<tr>\n ";
             echo "<td>",$row['UnitId'],"</td>\n";
             echo "<td>",$row['UnitName'],"</td>\n";
             echo "<td>",$row['UnitMetric'],"</td>\n";
             echo "</tr>\n ";
          }
        }
      }

      if (mysqli_connect_errno())
      {
         echo "Failed to connect to MySQL: " . mysqli_connect_error();
         //you need to exit the script, if there is an error
         exit();

      } else {
        //print the Sensors Table
        echo "<table border=\"5\">\n";
        echo "<tr>\n "
        ."<th scope=\"col\">Sensors Table</th>\n "
        ."<th scope=\"col\">SensorType</th>\n "
        ."<th scope=\"col\">SensorUnit</th>\n "
        ."</tr>\n ";
       echo "<tr>";
       echo "<td>";
       $query = "SELECT * FROM Sensors";
       $results = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error($conn), E_USER_ERROR);
       printdatapresensors();
       printdatasensors($results);
       printdatapost();
       echo "</td>\n";

       //print the SensorType Table
       echo "<td>";
       $query = "SELECT * FROM SensorType";
       $results = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error($conn), E_USER_ERROR);
       printdatapresensortype();
       printdatasensortype($results);
       printdatapost();
       echo "</td>\n";

       //print the SensorType Table
       echo "<td>";
       $query = "SELECT * FROM SensorUnit";
       $results = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error($conn), E_USER_ERROR);
       printdatapresensorunit();
       printdatasensorunit($results);
       printdatapost();
       echo "</td>\n";
       echo "</tr>";
       echo "</table>";
      }
      ?>
      <br />
      <form action="addnewsensorsresult.php" method="POST">
        <fieldset>
           <legend>Add New Sensors Form:</legend>
 					 <label name="sensorName">What Is The Sensors Name (Sensor Name in Sensors Table)?</label><input id="sensorName" name="sensorName"/><br />
 					 <label name="sensorCanID">What Is The Sensors CANID (CanId in Sensors Table)?</label><input id="sensorCanID" name="sensorCanID" placeholder="0x612" /><br />
           <label for="sensortypeID">What Is The Sensor Types ID (Sensor Type Id in SensorType Table) if the sensory type you have doesent exist please select the following checkbox)?</label>
           <select id="sensortypeID">
             <option value="" selected>No ID</option>
             <?php
             $query = "SELECT SensorTypeId FROM SensorType";
             $results = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error($conn), E_USER_ERROR);
             if ($results != null)
             {
               $sensorIDs = array();
               while ($row = mysqli_fetch_assoc($results))
               {
                  array_push($sensorIDs, $row['SensorTypeId']);
               }
               foreach ($sensorIDs as $value){
                 $dataused = (int)$value;
                  echo "<option value=\""
                   . $dataused , "\">"
                   . $dataused , "</option>";
               }
               echo "</select>";
             }

              ?>
              <br />
              <label for="notypeid">No Sensor Type Id</label>
              <input type="checkbox" id="notypeid" name="notypeid" value="notypeid"></input><br>

              <label id="sensordescriptionlbl" name="sensordescription">What Is The Sensors Description (Description in SensorType Table)</label><textarea id="sensordescription" rows="2" cols="50"></textarea><br />
              <label for="unitname">What is the sensors measurement UnitID (UnitID in SensorUnit Table) if the UnitID doesent exist please select the following checkbox</label>
              <select id="sensorunitid">
                <option value="" selected>No ID</option>
                <?php
                $query = "SELECT UnitId FROM SensorUnit";
                $results = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error($conn), E_USER_ERROR);
                if ($results != null)
                {
                  $sensorIDs = array();
                  while ($row = mysqli_fetch_assoc($results))
                  {
                     array_push($sensorIDs, $row['UnitId']);
                  }
                  foreach ($sensorIDs as $value){
                    $dataused = (int)$value;
                     echo "<option value=\""
                      . $dataused , "\">"
                      . $dataused , "</option>";
                  }
                  echo "</select>";
                }
                 ?>
                 <br />
              <label for="nomeasurementlbl">No Sensor Unit ID</label>
              <input type="checkbox" id="nomeasurement" name="nomeasurement" value="nomeasurement"></input><br>

              <label id="unitnamelbl" for="unitname">What Is The Unit Name (e.g Celcius, Kilometers, Heat/Time)</label><input id="unitname" name="unitname"/><br />
              <label id="unitmetriclbl" for="unitmetric">What Is The Unit Metric (e.g C, Km, deg/s)</label><input id="unitmetric" name="unitmetric"/><br />

 					 <button type="submit">Add New Sensor To Database</button>
          </fieldset>
      </form>

      <script>
      var cb = document.getElementById('notypeid');
      var cb2 = document.getElementById('nomeasurement');

      var tb = document.getElementById('sensordescription');
      var lb = document.getElementById('sensordescriptionlbl');

      var tbmeasure = document.getElementById('unitname');
      var lbmeasure = document.getElementById('unitmetric');
      var unitnamelbl = document.getElementById('unitnamelbl');
      var unitmetriclbl = document.getElementById('unitmetriclbl');

      cb.onchange = function() { // listen for event change
       if(!cb.checked) { // check state
         tb.style.visibility = 'hidden';
         lb.style.visibility = 'hidden';
       } else {
         tb.style.visibility = 'visible';
         lb.style.visibility = 'visible';
       }
     }

     cb2.onchange = function() { // listen for event change
      if(!cb2.checked) { // check state
        tbmeasure.style.visibility = 'hidden';
        lbmeasure.style.visibility = 'hidden';
        unitnamelbl.style.visibility = 'hidden';
        unitmetriclbl.style.visibility = 'hidden';
      } else {
        tbmeasure.style.visibility = 'visible';
        lbmeasure.style.visibility = 'visible';
        unitnamelbl.style.visibility = 'visible';
        unitmetriclbl.style.visibility = 'visible';
      }
    }


     window.onload = function(){
       if(!cb.checked) { // check state
         tb.style.visibility = 'hidden';
         lb.style.visibility = 'hidden';
       }else {
         tb.style.visibility = 'visible';
         lb.style.visibility = 'visible';
       }

       if(!cb2.checked) { // check state
         tbmeasure.style.visibility = 'hidden';
         lbmeasure.style.visibility = 'hidden';
         unitnamelbl.style.visibility = 'hidden';
         unitmetriclbl.style.visibility = 'hidden';
       } else {
         tbmeasure.style.visibility = 'visible';
         lbmeasure.style.visibility = 'visible';
         unitnamelbl.style.visibility = 'visible';
         unitmetriclbl.style.visibility = 'visible';
       }

     };
      </script>
</body>
</html>