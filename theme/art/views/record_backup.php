<?php

$author_field = $this->skylight_utilities->getField("Author");
$type_field = $this->skylight_utilities->getField("Type");
$bitstream_field = $this->skylight_utilities->getField("Bitstream");
$thumbnail_field = $this->skylight_utilities->getField("Thumbnail");


$type = 'Unknown';

if(isset($solr[$type_field])) {
    $type = "media-" . strtolower(str_replace(' ','-',$solr[$type_field][0]));
}


?>


<h1 class="itemtitle"><span class="icon <?php echo $type ?>"></span><?php echo $record_title ?></h1>
<div class="tags">
    <?php

    if (isset($solr[$author_field])) {
        foreach($solr[$author_field] as $author) {
            $orig_filter = preg_replace('/ /','+',$author, -1);
            $orig_filter = preg_replace('/,/','%2C',$orig_filter, -1);
            echo '<a href=\'./search/*/Author:"'.$orig_filter.'"\'>'.$author.'</a>';
        }
    }

    $date_field = $this->skylight_utilities->getField("Date");
    if (isset($solr[$date_field])) {
        foreach($solr[$date_field] as $date) {
            echo '<span>('.$date.')</span>';
        }
    }
    else {
        $date_field = $this->skylight_utilities->getField("Year");
        if (isset($solr[$date_field])) {
            foreach($solr[$date_field] as $date) {
                echo '<span>('.$date.')</span>';
            }
        }
    }

    ?>
</div>

<div class="content">

    <?php
    $abstract_field = $this->skylight_utilities->getField("Abstract");
    if(isset($solr[$abstract_field])) {
        ?> <h3>Abstract</h3> <?php
        foreach($solr[$abstract_field] as $abstract) {
            echo '<p>'.$abstract.'</p>';
        }
    }
    ?>

    <table>
        <caption>Description</caption>
        <tbody>
        <?php foreach($recorddisplay as $key) {

            $element = $this->skylight_utilities->getField($key);
            if(isset($solr[$element])) {
                echo '<tr><th>'.$key.'</th><td>';
                foreach($solr[$element] as $index => $metadatavalue) {
                    echo $metadatavalue;
                    if($index < sizeof($solr[$element]) - 1) {
                        echo '; ';
                    }
                }
                echo '</td></tr>';
            }

        } ?>
        </tbody>
    </table>

</div>



<?php if(isset($solr[$bitstream_field]) && $link_bitstream) {
?><div class="record_bitstreams"><h3>Digital Objects</h3><?php



    foreach($solr[$bitstream_field] as $bitstream) {

        $bitstreamLink = $this->skylight_utilities->getBitstreamLink($bitstream);
        $bitstreamLinkedImage = $this->skylight_utilities->getBitstreamLinkedImage($bitstream);


        $segments = explode("##", $bitstream);
        $filename = $segments[1];
        $handle = $segments[3];
        $seq = $segments[4];
        $handle_id = preg_replace('/^.*\//', '',$handle);
        $uri = './record/'.$handle_id.'/'.$seq.'/'.$filename;

        if (strpos($uri, ".jpg")> 0)
        {
            echo '<H1>IMAGE</H1>';
            echo '<img src = "'.$uri.'" height = "280">';


            echo '<p><span class="label"></span>'.$bitstreamLink.'
       (<span class="bitstream_size">';
            echo getBitstreamSize($bitstream);
            echo '</span>, <span class="bitstream_mime">';

            echo getBitstreamMimeType($bitstream);
            echo '</span>, <span class="bitstream_description">';
            echo getBitstreamDescription($bitstream);
            echo'</span>)</p>';

        }
    }
    foreach($solr[$bitstream_field] as $bitstream) {

        $bitstreamLink = $this->skylight_utilities->getBitstreamLink($bitstream);
        $bitstreamLinkedImage = $this->skylight_utilities->getBitstreamLinkedImage($bitstream);


        $segments = explode("##", $bitstream);
        $filename = $segments[1];
        $handle = $segments[3];
        $seq = $segments[4];
        $handle_id = preg_replace('/^.*\//', '',$handle);
        $uri = './record/'.$handle_id.'/'.$seq.'/'.$filename;


        if (strpos($uri, ".mp3")> 0)
        {
            echo '<h1>AUDIO</h1>';
            echo '<script src="http://api.html5media.info/1.1.6/html5media.min.js"></script>';
            echo '<audio src="'.$uri.'" controls preload></audio>';

        }
    }

    foreach($solr[$bitstream_field] as $bitstream) {

        $bitstreamLink = $this->skylight_utilities->getBitstreamLink($bitstream);
        $bitstreamLinkedImage = $this->skylight_utilities->getBitstreamLinkedImage($bitstream);


        $segments = explode("##", $bitstream);
        $filename = $segments[1];
        $handle = $segments[3];
        $seq = $segments[4];
        $handle_id = preg_replace('/^.*\//', '',$handle);
        $uri = './record/'.$handle_id.'/'.$seq.'/'.$filename;

        if (strpos($uri, ".mp4")> 0)
        {

            echo '<H1>VIDEO</H1>';

            echo '<script src="http://api.html5media.info/1.1.6/html5media.min.js"></script>';
            echo '<video width="320" height="200" controls> <source src="'.$uri.'" type="video/mp4">Sorry, it does not work</video>';

        }

        ?>
    <?php
    }
    echo '</div>';}?>
