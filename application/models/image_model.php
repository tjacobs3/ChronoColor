<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_model extends CI_Model {

    var $file_path   = '';
    var $image;
    var $color_histogram;
    var $histogram_partitions = 12;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function init($file_path)
    {
        $this->file_path = $file_path;
        if(preg_match("([^\s]+(\.(?i)(jpg|jpeg))$)", $file_path)) {
            $this->image = imagecreatefromjpeg($file_path);
        }
        else if(preg_match("([^\s]+(\.(?i)(png))$)", $file_path)) {
            $this->image = imagecreatefrompng($file_path);
        }
    }
    
    function get_average_color()
    {
        $r = 0;
        $g = 0;
        $b = 0;
        $count = 0;
        
        for($i = 0; $i < imagesx($this->image); $i++) {
            for($j = 0; $j < imagesy($this->image); $j++) {
                $rgb = imagecolorat($this->image, $i, $j);
                $r += ($rgb >> 16) & 0xFF;
                $g += ($rgb >> 8) & 0xFF;
                $b += $rgb & 0xFF;
                
                $count++;
            }
        }
        return array(floor($r / $count), floor($g / $count), floor($b / $count));
    }
    
    function analyze_color_histogram()
    {
        $top_colors = array();
        $size = $this->histogram_partitions;
        
        $histogram = array();
        for($i = 0; $i < $size; $i++) {
            for($j = 0; $j < $size; $j++) {
                for($k = 0; $k < $size; $k++) {
                    $h = (($i * (360 / $size)) + (($i + 1) * (360 / $size))) / 2;
                    $s = (($j * (1 / $size)) + (($j + 1) * (1 / $size))) / 2;
                    $v = (($k * (1 / $size)) + (($k + 1) * (1 / $size))) / 2;
                
                    $rgb = $this->hsv2rgb(array($h, $s, $v));
                    array_push($top_colors, array("rgb(" . round($rgb[0] * 255) . "," . round($rgb[1] * 255) . "," . round($rgb[2] * 255) . ")", $this->color_histogram[$i][$j][$k], $i, $j, $k));
                }
            }
        }
        
        usort($top_colors, array($this, 'sort_analyzed_colors'));
        $top_colors = array_reverse($top_colors);
        $count = 0;
        foreach ($top_colors as $key => $value) {
            if($value[1] <= 1) continue;
            $ret_array[$count] = $value;
            $count++;
        }
        
        for($i = 0; $i < 5; $i++) {
            $i1 = $ret_array[$i][2];
            $i2 = $ret_array[$i][3];
            $i3 = $ret_array[$i][4];
            for($j = $i + 1; $j < count($ret_array); $j++)
            {
                $j1 = $ret_array[$j][2];
                $j2 = $ret_array[$j][3];
                $j3 = $ret_array[$j][4];
                if($this->is_point_similar($i1, $i2, $i3, $j1, $j2, $j3))
                {
                    array_splice($ret_array,$j,1);
                    $j--;
                }
            }
        }
        
        return $ret_array;
    }
    
    function is_point_similar($i1, $j1, $k1, $i2, $j2, $k2)
    {
        return sqrt((($i1 - $i2) * ($i1 - $i2)) + (($j1 - $j2) * ($j1 - $j2)) + (($k1 - $k2) * ($k1 - $k2))) <= 1;
    }
    
    function sort_analyzed_colors($a, $b)
    {
        if ($a[1] == $b[1]) {
            return 0;
        }
        return ($a[1] < $b[1]) ? -1 : 1;
    }

    
    function create_hsb_histogram()
    {
        $size = $this->histogram_partitions;
        $hue_divider_size = 360 / $size;       // Hue is 0 .. 360 degrees
        $saturation_divider_size = 1 / $size;  // Saturation is 0 .. 1
        $brightness_divider_size = 1 / $size;  // Brightness is 0 .. 1
    
        $histogram = array();
        for($i = 0; $i < $size; $i++) {
            $histogram[$i] = array();
            for($j = 0; $j < $size; $j++) {
                $histogram[$i][$j] = array();
                for($k = 0; $k < $size; $k++) {
                    $histogram[$i][$j][$k] = 0;
                }
            }
        }
        
        for($i = 0; $i < imagesx($this->image); $i++) {
            for($j = 0; $j < imagesy($this->image); $j++) {
                $rgb = imagecolorat($this->image, $i, $j);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $hsv = $this->rgb2hsv(array($r, $g, $b));
                $h = $hsv[0];
                $s = $hsv[1];
                $v = $hsv[2];
                
                $hue_index = min(floor($h / $hue_divider_size), $size - 1);
                $saturation_index = min(floor($s / $saturation_divider_size), $size - 1);
                $brightness_index = min(floor($v / $brightness_divider_size), $size - 1);

                $histogram[$hue_index][$saturation_index][$brightness_index] += 1;
            }
        }
        
        $this->color_histogram = $histogram;
    }
    
    // $c = array(255, 255, 255)
    function rgb2hsv($c) { 
        list($r,$g,$b)=$c;
        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;
        $v=max($r,$g,$b); 
        $t=min($r,$g,$b); 
        $s=($v==0)?0:($v-$t)/$v; 
        if ($s==0) 
            $h=0; 
        else { 
            $a=$v-$t; 
            $cr=($v-$r)/$a; 
            $cg=($v-$g)/$a; 
            $cb=($v-$b)/$a; 
            $h=($r==$v)?$cb-$cg:(($g==$v)?2+$cr-$cb:(($b==$v)?$h=4+$cg-$cr:0)); 
            $h=60*$h; 
            $h=($h<0)?$h+360:$h; 
        } 
        return array($h,$s,$v); 
    } 

    // $c = array($hue, $saturation, $brightness) 
    // $hue=[0..360], $saturation=[0..1], $brightness=[0..1] 
    function hsv2rgb($c) { 
        list($h,$s,$v)=$c; 
        if ($s==0) 
            return array($v,$v,$v); 
        else { 
            $h=($h%=360)/60; 
            $i=floor($h); 
            $f=$h-$i; 
            $q[0]=$q[1]=$v*(1-$s); 
            $q[2]=$v*(1-$s*(1-$f)); 
            $q[3]=$q[4]=$v; 
            $q[5]=$v*(1-$s*$f); 
            //return(array($q[($i+4)%5],$q[($i+2)%5],$q[$i%5])); 
            return(array($q[($i+4)%6],$q[($i+2)%6],$q[$i%6])); //[1] 
        } 
    } 
}
?>