<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Orbitron:700' rel='stylesheet' type='text/css'>
  </head>
  <body>
      <div class="container">
          <div class="row">
              <div class="col-sm-12" style="position: relative; top: 0%; left: 0%; height: 50px; width: 100%;">
                  <div style="font-family: Raleway; font-size: 150%; position: absolute; top: 600%; left: 13%;">
                      Enter a single variable polynomial (e.g. 3x^2 + 4x^5 + 2y^3 + 4) :
                  </div>
              </div>
          </div>
          <div class="row">
              <form method="post" action="derivatives.php">
                  <div class="col-sm-4" style="position: relative; top: 0%; left: 0%; height: 50px; width: 100%;">
                      <input type="text" name="poly" style="font-family: Raleway; font-size: 150%; position: absolute; top: 600%; left: 13%; width: 50%;">
                  </div>
                  <div class="col-sm-4" style="position: relative; top: 0%; left: 0%; height: 50px; width: 100%;">
                      <input type="submit" value="Enter" class="btn btn-danger" style="font-family: Montserrat; position: absolute; top: 612%; left: 13%;">
                  </div>
              </form>
              <form method="post" action="front.html">
              <div class="col-sm-4" style="position: relative; top: 0%; left: 3%; height: 50px; width: 100%;">
                  <input type="submit" value="Back" class="btn btn-primary" style="font-family: Montserrat; position: absolute; top: 511%; left: 17%;">
              </div>
              </form>
          </div>
          <?php 
            $subject;
            if(!empty($_POST['poly'])){
                is_poly();
                get_derivative();
            }
          
            function is_poly(){
                global $subject;
                $pattern = "/((([1-9]*([a-zA-Z][\^][1-9]+)?)*[\s]*[+-][\s]*)+([1-9]*([a-zA-Z][\^][1-9]+)?)+|([1-9]*([a-zA-Z][\^][1-9]+)?))/";
                $subject = $_POST['poly'];
                preg_match($pattern, $subject, $matches);
                if(strcmp($matches[0], $subject) !== 0){
                    echo "<div class='row'>
                            <div class='col-sm-12' style='position: relative; top: 0%; left: 0%; height: 50px; width: 100%;'>
                                <div style='font-family: Montserrat; font-size: 150%; color: red; position: absolute; top: 540%; left: 13%;'> \"" . $subject . "\" is not a polynomial or is not of the right form.</div>
                            </div>
                          </div>";
                    exit();
                }
            }
          
            function get_derivative(){
                global $subject;
                $new_poly_array = array();
                $curr_subject = $subject;
                $counter = 0;
                
                //breaks the polynomial into an array of terms and trims white space
                $curr_subject = str_replace("+", "&", $curr_subject);
                $curr_subject = str_replace("-", "&", $curr_subject);
                $poly_array = explode("&", $curr_subject); 
                foreach($poly_array as $term){
                    trim($term);
                }
                
                //creates string of the signs
                $signs = preg_replace("/[a-zA-Z]/", "", $subject);
                $signs = preg_replace("/\^/", "", $signs);
                $signs = preg_replace("/[0-9]+/", "", $signs);
                $signs = str_replace(" ", "", $signs);
                
                foreach($poly_array as $term){
                    $variable_array = array();
                    $pattern = "/[a-zA-Z]/";
                    preg_match($pattern, $term, $variable_array);
                    if(!empty($variable_array[0])){
                        $delimiter = $variable_array[0] . "^";
                        $coeffExponent_array = explode($delimiter, $term); //stores the coefficient and exponent values
                        //handles coefficient = 0
                        if(is_numeric($coeffExponent_array[0]))
                            $new_coeff = $coeffExponent_array[0] * $coeffExponent_array[1];
                        else
                            $new_coeff = $coeffExponent_array[1];
                        
                        $new_exponent = ($coeffExponent_array[1] - 1);
                        $new_poly_array[$counter] = $new_coeff . $variable_array[0] . "^" . $new_exponent;
                        $counter++;
                    }
                    else
                        $delimiter = null;
                }
                
                //builds final derivative
                $derivative = null;
                $size = sizeof($new_poly_array); 
                for($i = 0; $i < $size; $i++){
                    if($i != ($size-1))
                        $derivative = $derivative . $new_poly_array[$i] . " " . $signs[$i] . " ";
                    else
                        $derivative = $derivative . $new_poly_array[$i];
                }
                
                if($derivative == null){
                    echo "<div class='row'>
                            <div class='col-sm-12' style='position: relative; top: 0%; left: 0%; height: 50px; width: 100%;'>
                                <div style='font-family: Orbitron; font-size: 150%; color: green; position: absolute; top: 540%; left: 13%;'>Original Polynomial: " . $subject . "</div>
                            </div>
                           </div>";  
                    echo "<div class='row'>
                            <div class='col-sm-12' style='position: relative; top: 0%; left: 0%; height: 50px; width: 100%;'>
                                <div style='font-family: Orbitron; font-size: 150%; color: green; position: absolute; top: 540%; left: 13%;'>Derivative: 0</div>
                            </div>
                           </div>";  
                }
                else{
                    echo "<div class='row'>
                            <div class='col-sm-12' style='position: relative; top: 0%; left: 0%; height: 50px; width: 100%;'>
                                <div style='font-family: Orbitron; font-size: 150%; color: green; position: absolute; top: 540%; left: 13%;'>Original Polynomial: " . $subject . "</div>
                            </div>
                           </div>";  
                    echo "<div class='row'>
                            <div class='col-sm-12' style='position: relative; top: 0%; left: 0%; height: 50px; width: 100%;'>
                                <div style='font-family: Orbitron; font-size: 150%; color: green; position: absolute; top: 540%; left: 13%;'>Derivative: " . $derivative . "</div>
                            </div>
                           </div>";  
                }
            }
          ?>
      </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>