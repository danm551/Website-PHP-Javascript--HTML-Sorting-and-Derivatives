<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Sigmar+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        function progressBar(id, n){
            var progressBar = document.getElementById(id, n);
            var currentProgress = progressBar.style.getPropertyValue("width");
            
            if(n > 99){
                if(currentProgress == "20%")
                   progressBar.setAttribute("style", "width: 25%");
                else if(currentProgress == "25%")
                    progressBar.setAttribute("style", "width: 50%");
                else if(currentProgress == "50%")
                    progressBar.setAttribute("style", "width: 75%");
                else{
                    progressBar.setAttribute("style", "width: 100%");
                    progressBar.setAttribute("class", "progress-bar progress-bar-success progress-bar-striped");
                    if(id == "RandProgressBar")
                        progressBar.innerHTML = "Getting Some Numbers - DONE!";
                    else
                        progressBar.innerHTML = "Sorting Numbers - DONE!"
                }
            }
            else{
                progressBar.setAttribute("style", "width: 100%");
                progressBar.setAttribute("class", "progress-bar progress-bar-success progress-bar-striped");
                if(id == "RandProgressBar")
                    progressBar.innerHTML = "Getting Some Numbers - DONE!";
                else
                    progressBar.innerHTML = "Sorting Numbers - DONE!"
            }
        }
        
        function postTime(elapsed){
            var parent = document.getElementById("elapsed");
            parent.innerHTML = "Elapsed Time (in seconds) : " + elapsed;
        }
    </script>
  </head>
  <body>
      <div class="container">
          <div class="row">
              <div class="col-sm-12" style="position: relative; top: 0%; left: 0%; height: 50px; width: 100%;">
                  <div style="font-family: Montserrat; position: absolute; top: 600%; left: 30%;">How many random numbers would you like to sort?</div>
              </div>
          </div>
          <div class="row">
              <form method="post" action="selectionSort.php">
                  <div class="col-sm-4" style="position: relative; top: 0%; left: 0%; height: 50px; width: 100%;">
                      <input type="number" min="2" max="900000" name="n" style="font-family: Montserrat; position: absolute; top: 600%; left: 30%;" required>
                  </div>
                  <div class="col-sm-4" style="position: relative; top: 0%; left: 0%; height: 50px; width: 100%;">
                      <input type="submit" value="Enter" class="btn btn-danger" style="font-family: Montserrat; position: absolute; top: 492%; left: 38%;">
                  </div>
              </form>
              <form method="post" action="front.html">
              <div class="col-sm-4" style="position: relative; top: 0%; left: 0%; height: 50px; width: 100%;">
                  <input type="submit" value="Back" class="btn btn-primary" style="font-family: Montserrat; position: absolute; top: 391%; left: 45%;">
              </div>
              </form>
          </div>
      </div>
            <?php
                set_time_limit(0);
                error_reporting(1);
                $number_array = array(); //global variable by default
                if(!empty($_POST['n'])){
                    echo "<div class='container' style='position: relative; top: 500px;'>
                            <div class='row'>";
                    $n = $_POST['n'];
                    get_random_numbers($n);
                    selection_sort();
                    echo "</div></div>";
                }
            
                function get_random_numbers($n){
                    global $number_array; //function refers to the global variable by using the keyword "global" in declaration
                    global $n;
                    echo "<div class='progress'>
                            <div class='progress-bar progress-bar-striped active' role='progress bar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 20%' id='RandProgressBar'>
                                Getting Some Numbers
                            </div>
                          </div>
                          <div class='progress'>
                            <div class='progress-bar progress-bar-striped active' role='progress bar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 20%' id='SortProgressBar'>
                                Sorting Numbers
                            </div>
                          </div>
                          <div id='elapsed' style='font-family: Montserrat;'>
                          </div>";
                    for($i = 0; $i < $n; $i++){
                        if($i == (floor($n*.25)) || $i == (floor($n*.50)) || $i == (floor($n*.75)) || $i == (floor($n-1))){
                            echo "<script type='text/javascript'>
                                    progressBar(\"RandProgressBar\", $n);
                                  </script>";
                            flush();    //flush() and ob_flush() allow the progress bar to be updated during a long loop.
                            ob_flush();
                        }
                        $random = rand(1, $n);
                        array_push($number_array, $random);
                    }
                    echo "<div class='row' style='margin-top: 50px; margin-bottom: 50px;'>
                            <div class='col-sm-12'>";
                    print_array(0, 0);
                    echo "</div></div>";
                }
      
                function bubble_sort(){
                    global $number_array;
                    global $n;
                    $size = sizeof($number_array);
                    $swapped = true;
                    $j = 0;
                    $progress = 0;
                    $time_start = microtime(true);
                    while($swapped){
                        $swapped = false;
                        $j++;
                        for($i = 0; $i < ($size - $j); $i++){
                            if($number_array[$i] > $number_array[($i + 1)]){
                                $temp = $number_array[$i];
                                $number_array[$i] = $number_array[($i + 1)];
                                $number_array[($i + 1)] = $temp;
                                $swapped = true;
                                $progress++;
                                if($progress == floor((pow($n/2, 2)*.25)) || $progress == floor((pow($n/2, 2)*.50)) || $progress == floor((pow($n/2, 2)*.75))){
                                    echo "<script type='text/javascript'>
                                            progressBar(\"SortProgressBar\", $n);
                                          </script>";
                                    flush();   
                                    ob_flush();
                                }
                            }
                        }
                    }
                    echo "<script type='text/javascript'>
                            progressBar(\"SortProgressBar\");
                          </script>";
                    $elapsed = (microtime(true)-$time_start);
                    echo "<script type='text/javascript'>
                            postTime($elapsed);
                          </script>";
                    echo "<div class='row' style='margin-top: 50px; margin-bottom: 50px;'>
                            <div class='col-sm-12'>";
                    print_array(0, 1);
                    echo "</div></div>";
                }
                
                function print_array($curr_index, $status){
                    global $number_array;
                    global $n;
                    $size = sizeof($number_array);
                    $counter = 0;
                    if($status === 0){
                        $header = "UNSORTED";
                        $color = "990000";
                    }
                    else{
                        $header = "SORTED";
                        $color = "2a874f";
                    }
                    if($curr_index === 0)
                        echo "<table border=1 width=100%' id='$header'>
                                <tr>
                                    <th colspan='30' style='text-align: center;'>" . $header . "</th>
                                </tr>";
                    echo "<tr>";
                    for($i = $curr_index; $i < $size; $i++){
                        echo "<td align=center><p style='font-family: Sigmar One; font-size: 100%; color: #" . $color . ";'>" . $number_array[$i] . "</p></td>";
                        $counter++;
                        if($counter == 30){
                            echo "</tr>";
                            print_array(($counter+$curr_index), $status);
                            break;
                        }
                    }
                    echo "</tr></table>";
                    return;
                }
            ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>