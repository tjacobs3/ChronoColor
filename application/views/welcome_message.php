<?php $this->load->view('common/header') ?>

        <div class="contentblock">
        
            <div>
                <h2>Pablo Piccaso</h2>
                <p class="date">1881&ndash;1973</p>
            </div>
            <div class="divider_small"></div>
            
            <div class="timeline">
                <div class="periods">
                    <div class="timeperiod" style="left:0px;">
                        <div class="vdivider"></div>
                        <h3>Early Years</h3>
                        <p class="date">1889&ndash;1900</p>
                    </div>
                    <div class="timeperiod" style="left:117px;">
                        <div class="vdivider"></div>
                        <h3>Blue</br>Period</h3>
                        <p class="date">1901&ndash;04</p>
                    </div>
                    <div class="timeperiod" style="left:180px;">
                        <div class="vdivider"></div>
                        <h3>Rose</br>Period</h3>
                        <p class="date">1904&ndash;06</p>
                    </div>
                    <div class="timeperiod" style="left:250px;">
                        <div class="vdivider"></div>
                        <h3>Cubism</h3>
                        <p class="date">1909&ndash;1912</p>
                    </div>
                    <div class="timeperiod" style="left:600px;">
                        <div class="vdivider"></div>
                        <h3>Classicism and Surrealism</h3>
                        <p class="date">1920&ndash;1940</p>
                    </div>
                    <div class="timeperiod" style="left:800px;">
                        <div class="vdivider"></div>
                        <h3>Neo-expressionism</h3>
                        <p class="date">1970</p>
                    </div>
                </div><span class="clear"></span>
                <div class="colorbar">
                    <?php
                    // THIS IS A SAMPLE
                    // TODO: Load artist data into js
                    $count = 0;
                    foreach ($artist_data as $row)
                    {
                        $r = round($row->r);
                        $g = round($row->g);
                        $b = round($row->b);
                        echo "<div class='timelineBlock' style='left: " . $count * 4 . "px; background-color: rgb({$r}, {$g}, {$b});' data-year='{$row->year}' data-quarter='{$row->quarter}' ><!-- {$row->year} {$row->quarter} --></div>";
                        $count += 1;
                    }
                    ?>
                </div>
            </div>
            
            <div class="selectiontext">
                <h3>1918 - Quarter 1</h3>
            </div>
            
            <div class="detailblock">
        
            </div>
            
            <span class="clear"></span>
        </div>
    </div>

  </div>
<?php $this->load->view('common/footer') ?>