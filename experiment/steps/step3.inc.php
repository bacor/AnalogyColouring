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
       E = new Experiment('experiment-wrapper', 'experiment-form', <?= $trials[$trial][0] ?>, <?= $trials[$trial][1]?>, 'swatches')
    }
    </script>
</head>

<body>
<div id="wrapper">
    <div id="sidebar">
		<h1><strong>Trial <?= $trial + 1 ?></strong> &mdash; <?= $num_trials - $trial - 1 ?> more to go</h1>
        <p>Your task is as before: paint the picture at the bottom in a way similar to the picture above. That is, if you find something in the bottom picture similar to something in the top picture, give it the same colour.
      <!--
  The one on the top is coloured, the one on the bottom is not.
        Your task is to colour the bottom picture. Colour the objects in the bottom picture the same colour as objects in the top picture, if you find them similar
-->
        You don't <em>have</em> to colour any objects.
        </p>
        
        Here are the colours you can use:
        <div id="swatches"></div>
        
        <form id="experiment-form" method="post">
        	<input type="hidden" name="subject_id" value="<?= $subject_id; ?>" />
        	<input type="hidden" name="trial" value="<?= $trial + 1 ?>" />
        	<?php if($trial == $num_trials -1 ) : ?>
        	<input type="hidden" name="step" value="4" />
            <input type="submit" class="btn" value="Nearly done! Go to the last step.">
        	<?php else : ?>
        	<input type="hidden" name="step" value="3" />
        	<input type="hidden" name="trials" value='<?= json_encode($trials); ?>' />
        	<input type="submit" class="btn" value="Done? Go to the next pictures.">
        	<?php endif; ?>
        </form>
        
        <div id="footer">
    	<p>Experimental design by Bas Cornelissen, Iliana Gioulatou, Jori Jansen and Arnold Kochari. For questions, please contact Bas via info at, well, bascornelissen.nl.</p>
    </div>
    </div>

    <div id="experiment-wrapper" class="cf"></div>
</div>
 
  	
</body>
</html>