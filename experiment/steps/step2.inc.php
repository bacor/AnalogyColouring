<?php if(isset($ACTIVE) === False) die('Bye bye!'); ?>

<!doctype html>
<html>
  
<head>
    <title><?= $page_title ?></title>
    <script type="text/javascript" src="raphael.js"></script>
    <script type="text/javascript" src="AnalogyColouring.js"></script>
    <script type="text/javascript" src="data.js"></script>
    <link href="style.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript">
    window.onload = function() {
       E = new Experiment('experiment-wrapper', 'experiment-form', DEMO2, DEMO1, 'swatches')
    }
    </script>
</head>

<body>
<div id="wrapper">
    <div id="sidebar">
		<h1><strong>What do you have to do?</strong></h1>
        <p>        
        The goal of the task is to match what is going on in the top picture, to what is going on in the picture below. You can do so by giving 'similar' things the same colour. In other words, you have to color the bottom picture in a similar way as the top picture.</p>
	
		<p>Try to do that here. The ball should be green, the person holding the ball dark blue, and the other one light blue. Do you see why?</p>
     
   
        <p>Note that you don't have to color everything: there might be things that have no counterpart in the picture above (such as the basket). It could even happen that there is nothing remotely similar to the picture above. If that's what you think, don't colour anything.</p>

        <div id="swatches"></div>

        
<!--         <p>If you feel you have coloured all objects that are somehow similar to objects in the top most picture, you can move on.</p> -->
        <form id="experiment-form" method="post">
        	<input type="hidden" name="step" value="3" />
<!--         	<input type="hidden" name="trial" value="1" /> -->
        	<input type="hidden" name="subject_id" value="<?= $subject_id; ?> " />
        	<input type="hidden" name="trials" value='<?= json_encode($trials); ?>' />
            <input type="submit" class="btn" value="Clear, start the experiment!" />
        </form>        
        
        <div id="footer">
    	<p>Experimental design by Bas Cornelissen, Iliana Gioulatou, Jori Jansen and Arnold Kochari. For questions, please contact Bas via info at, well, bascornelissen.nl.</p>
    </div>
    </div>

    <div id="experiment-wrapper" class="cf"></div>
</div>
 
  	
</body>
</html>