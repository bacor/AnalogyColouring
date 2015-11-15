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
       E = new Experiment('experiment-wrapper', 'experiment-form', DEMO1, DEMO1, 'swatches')
    }
    </script>
</head>

<body>
<div id="wrapper">
    <div id="sidebar">
		<h1><strong>How does it work?</strong></h1>
        <p>On the right, you will always see two pictures. The top picture is already coloured, the bottom one is not. Your task will be to colour the bottom picture. How does that work?</p>
                
        <p>First, you have to select a colour:</p>	
        
        <div id="swatches"></div>
        <p>Then you have to click on the thing in the bottom picture that you want to paint. Try it! Paint some other things in other colours, if you like.</p>
        
        <p>As you maybe noticed, you can use every color only once.</p>
        <form id="experiment-form" method="post">
        	<input type="hidden" name="step" value="2" />
<!--         	<input type="hidden" name="trial" value="1" /> -->
        	<input type="hidden" name="subject_id" value="<?= $subject_id; ?> " />
        	<input type="hidden" name="trials" value='<?= json_encode($trials); ?>' />
            <input type="submit" class="btn" value="Let's do another example">
        </form>        
        
        <div id="footer">
    	<p>Experimental design by Bas Cornelissen, Iliana Gioulatou, Jori Jansen and Arnold Kochari. For questions, please contact Bas via info at, well, bascornelissen.nl.</p>
    </div>
    </div>

    <div id="experiment-wrapper" class="cf"></div>
</div>
 
  	
</body>
</html>