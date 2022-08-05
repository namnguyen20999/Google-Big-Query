<?php 
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'pagination.php'
?>
<!DOCTYPE html>
<html>
<head>
    <title>Big Query Test</title>
    <meta charset='UTF-8'>
    
    <link href='https://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' type='text/css' href='/css/style.css'>
</head>
<body>
    <div id='header'>
        Big Query Result
    </div>
    
    <div class='content'>

    <!-- Search form -->
    <form name="search" method="POST">
        Search by name: <input type="text" name="search_box" value=""/>
        <input type="submit" name="search" value="Search the table..."/>
    </form>
    <br>
    <!-- Filter data  -->
    <form name="filter" method="POST">
        Filtered by Country:
        <select name="Country">
            <option value="">Select country</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Thailand">Thailand</option>
            <option value="Laos">Laos</option>
            <option value="Vietnam">Vietnam</option>
            <option value="Cambodia">Cambodia</option>
            <option value="China">China</option>
        </select>  
        <input type="submit" name="filterSubmit">
    </form>

    <br />
    <?php
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Bigquery::BIGQUERY);
        $bigquery = new Google\Service\Bigquery($client);
        $projectId = 'assignment-1-358014';

        $request = new Google\Service\Bigquery\QueryRequest();
        $str = '';

        $request->setQuery("SELECT * FROM [Mekong.project] LIMIT 180");

        // Page function
        if(isset($_GET["page"])){
            $page=$_GET["page"];
            if($page != 1){
                $row_skip = $page * 180;
                $row_limit = ($page + 1) * 180;
                $request->setQuery("SELECT * FROM [Mekong.project] LIMIT $row_limit OFFSET $row_skip");
            } else {
                $request->setQuery("SELECT * FROM [Mekong.project] LIMIT 180"); 
            }
        }

                // Search function
        if(isset($_POST['search'])){
            $value = $_POST['search_box'];
            if($value){
                $request->setQuery("SELECT * FROM [Mekong.project] WHERE Project_Name = '$value'");

            } else {
                echo "No data is founded";
            }
        }

                // Filter function
        if(isset($_POST['filterSubmit'])){
            $value = $_POST['Country'];
            if($value){
                $request->setQuery("SELECT * FROM [Mekong.project] WHERE Country = '$value'");
                } else {
                    echo "No data is founded";
                }
            }

        $response = $bigquery->jobs->query($projectId, $request);
        $rows = $response->getRows();


        $str = "<table>".
        "<tr>" .
        "<th>Project_Name</th>" .
        "<th>Subtype</th>" .
        "<th>Current_Status</th>" .
        "<th>Capacity__MW_</th>" .
        "<th>Year_of_Completion</th>" .
        "<th>Country_list_of_Sponsor_Developer</th>" .
        "<th>Sponsor_Developer_Company</th>" .
        "<th>Country_list_of_Lender_Financier</th>" .
        "<th>Lender_Financier_Company</th>" .
        "<th>Country_list_of_Construction_EPC</th>" .
        "<th>Construction_Company_EPC_Participant</th>" .
        "<th>Country</th>" .
        "<th>Province_State</th>".
        "<th>District</th>" .
        "<th>Tributary</th>" .
        "<th>Latitude</th>" .
        "<th>Longitude</th>" .
        "<th>Proximity</th>" .
        "<th>Avg__Annual_Output__MWh_</th>" .
        "<th>Data_Source</th>" .
        "<th>Announcement_More_Information</th>" .
        "<th>Link</th>" .
        "<th>Latest_Update</th>" .
        "</tr>";
        
        foreach ($rows as $row)
        {
            $str .= "<tr>";

            foreach ($row['f'] as $field)
            {
                $str .= "<td>" . $field['v'] . "</td>";
            }
            $str .= "</tr>";
        }

        $str .= '</table></div>';

        echo $str;
        pagination();
    ?>
    </div>
</body>
</html>
