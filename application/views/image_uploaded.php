<html>
<head>
<title>Upload Form</title>
</head>
<body>

<h3>Your file was successfully uploaded!</h3>

<ul>
<?php foreach ($upload_data as $item => $value):?>
<?php
    if($item == "average_color")
    {
        $colorString = "rgb(" . $value[0] . ", " . $value[1] . ", " . $value[2] . ")";
        echo "<li style='background-color: " . $colorString . "'>Average Color: ". $colorString. "</li>";
    }
    else if($item == "top_colors")
    {
        $count = count($value);
        echo "<li style='background-color: " . $value[0][0] . "'>Color Palette 1: ". $value[0][0] . "</li>";
        echo "<li style='background-color: " . $value[1][0] . "'>Color Palette 2: ". $value[1][0] . "</li>";
        echo "<li style='background-color: " . $value[2][0] . "'>Color Palette 3: ". $value[2][0] . "</li>";
        echo "<li style='background-color: " . $value[3][0] . "'>Color Palette 4: ". $value[3][0] . "</li>";
        echo "<li style='background-color: " . $value[4][0] . "'>Color Palette 5: ". $value[4][0] . "</li>";
    }
    else
    {
        echo "<li>" . $item . ": " . $value . "</li>";
    }
?>
<?php endforeach; ?>
</ul>

</body>
</html>