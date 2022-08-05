<?php
    require_once 'vendor/autoload.php';
?>
<?php
    function pagination(){
        $rows_per_page = 200;
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Bigquery::BIGQUERY);
        $bigquery = new Google\Service\Bigquery($client);
        $projectId = 'assignment-1-358014';

        $request = new Google\Service\Bigquery\QueryRequest();
        $request->setQuery("SELECT COUNT(*) FROM [Mekong.project]");
        $respone = $bigquery->jobs->query($projectId, $request);
        $rows_num = $respone->getRows();

        foreach ($rows_num as $nums){
            foreach ($nums['f'] as $num){
                $total_page = ceil($num['v'] / $rows_per_page);
                $page = $_GET['page'];
                if ($page > 1){
                    echo "<a href='index.php?page=".($page-1)."' class='btn'>Previous</a>";
                }
                for($i=1;$i<=$total_page;$i++){
                    echo "<a href='index.php?page=".$i."' class='btn'>$i</a>";
                }
                if ($page <= 10){
                    echo "<a href='index.php?page=".($page+1)."' class='btn'>Next</a>";
                }
            }
        }
    }
?>