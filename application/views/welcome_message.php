<?php $this->load->view('common/header') ?>
<?php
// THIS IS A SAMPLE
// TODO: Load artist data into js
foreach ($artist_data as $row)
{
    $r = round($row->r);
    $g = round($row->g);
    $b = round($row->b);
    echo "<div style='background-color: rgb({$r}, {$g}, {$b}); width:6px; height: 60px; float: left;' ><!-- {$row->year} {$row->quarter} --></div>";
}
?>
<div class="contentblock">

    <div>
        <h2>Pablo Piccaso</h2>
        <p class="date">1881&ndash;1973</p>
    </div>
    <div class="divider_small"></div>
    
    <div class="timeline">
        <div class="period">
            <div class="vdivider"></div>
            <div class="timeperiod">
                <h3>Early Years</h3>
                <p class="date">1889&ndash;1900</p>
            </div>
            <span class="clear"></span>
        </div>
        <div class="colorbar">
        </div>
    </div>
    
    <div class="detailblock">
        <div class="detailcontainer">
            <div class="arrow">
                <img src="img/arrowl.png" width="52" height="44" alt="" />
            </div>
        
            <div class="detail">
        
                <div class="detailbox">
            
                    <div class="workimg">
                        <img src="img/workimg.jpg" width="248" height="300" alt="" />
                    </div>
                
                    <div class="workcolors">
                        <div class="workcolor" id="workcolor1"></div>
                        <div class="workcolor" id="workcolor2"></div>
                        <div class="workcolor" id="workcolor3"></div>
                        <div class="workcolor" id="workcolor4"></div>
                    </div>
                
                </div>
            
            <div class="detailtext">
                <h3 class="worktitle">Portrait de Sebastià Junyent</h3>
                <p class="workdate">1904</p>
                <p class="workdescription">Oil on canvas, 73 x 60 cm</p>
            </div>
            
        </div>
        
        <div class="arrow">
        <img src="img/arrowr.png" width="52" height="44" alt="" />
        </div>
        
        </div>

    </div>
    <span class="clear"></span>
</div>
<?php $this->load->view('common/footer') ?>