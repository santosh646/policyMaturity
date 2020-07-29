<?php
// <style>
// .policyDetailsTable tr th,policyDetailsTable tr td{

//     border:1px solid #eaeaea;
// }

// </style>
$filename = 'C:/Users/santosh/project-root/app/MaturityData.csv';

// if($filename==''){
//     echo "No such file or directory";
// }

// The nested array to hold all the arrays
$the_big_array = [];

// Open the file for reading
if (($h = fopen("{$filename}", "r")) !== false)
{
    // Each line in the file is converted into an individual array that we call $data
    // The items of the array are comma separated
    echo   "<table class='policyDetailsTable'><thead><th>Policy Number</th><th>Maturity Bonus</th></thead><tbody>";
    
    while (($data = fgetcsv($h, 1000, ",")) !== false)
    {
        //print_r($data[0]);
        $a=2;$b=3;
        alert($a,$b);
        $managementFee = managementFee($data);
        $discretionaryBonus = discretionaryBonus($data);
        $uplift = uplift($data);
        $maturityBonus = maturityBonus($managementFee, $discretionaryBonus, $data, $uplift);
        // Each individual array is being pushed into the nested array
        //$the_big_array[] = $data;
       echo "<tr><td>". $data[0] . "</td><td>" . $maturityBonus ."</td></tr>";
       $the_big_array[] =   array($data[0],$maturityBonus);
//        $xml = new SimpleXMLElement('<root/>');
// array_walk_recursive($data[0], array ($xml, 'addChild'));
// print $xml->asXML();
        
    }
   echo "</tbody></table>";
   //echo "bigarray",$the_big_array;
 

// initializing or creating array


// creating object of SimpleXMLElement
$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');

// function call to convert array to xml
array_to_xml($the_big_array,$xml_data);

//saving generated xml file; 
$result = $xml_data->asXML('C:/Users/santosh/project-root/app/name.xml');

    // Close the file
    fclose($h);
}

// Display the code in a readable format
echo "<pre>";
var_dump($the_big_array);
echo "</pre>";

function alert($a,$b){
    return $a+$b;
}

function managementFee($data)
{

    if (substr($data[0], 0, 1) == "A")
    {
        return .03;
    }
    else if (substr($data[0], 0, 1) == "B")
    {
        return .05;
    }
    else if (substr($data[0], 0, 1) == "C")
    {
        return .07;
    }
}

   
function discretionaryBonus($data)
{


    if (substr($data[0], 0, 1) == "A" && strtotime(str_replace('/', '-', $data[1])) < strtotime('01-01-1990'))
    {
        return $data[4];
		
    }
    else if (substr($data[0], 0, 1) == "B" && $data[3] == 'Y')
    {
        return $data[4];
    }
    else if (substr($data[0], 0, 1) == "C" && $data[3] == 'Y' && strtotime(str_replace('/', '-', $data[1])) >= strtotime('01-01-1990'))
    {
        return $data[4];
    }
    else
    {
        return 0;
    }

}
function uplift($data)
{
    return (int)1 + ((int)$data[5] / 100);
}
function maturityBonus($managementFee, $discretionaryBonus, $data, $uplift)
{

    return ((((int)$data[2] - ((int)$data[2] * (float)$managementFee)) + (float)$discretionaryBonus)) * (float)$uplift;

}
function array_to_xml( $the_big_array, &$xml_data ) {
    foreach( $the_big_array as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}

?>
