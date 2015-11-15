<?php if(isset($ACTIVE) === False) die('Bye bye!'); ?>

<!doctype html>
<html>
  
<head>
    <title><?= $page_title ?></title>
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>

<body>
<div id="wrapper">
    <div id="sidebar" class="wide">
		<h1><strong>Colouring</strong></h1>
        <p>First of all, thank you very much for participating in this experiment. It's going to be fun! Do you remember the the good old rainy days when you, five years old, were colouring, colouring and colouring? Well, this is that, all over again!</p>
        
        <p>Your task is to colour <?= $num_trials ?> pictures, that's it. Well, not entirely: we will also ask you to answer some questions at the end.</p>

        <p>Try to finish the experiment without breaks, stay focussed and please don't consult others.</p>
        
        <p>Finally, we will be very careful with your data, which will remain completely anonymous (unless you provide an email address at the end)</p>

        <form id="experiment-form" method="post">
        	<input type="hidden" name="step" value="1" />
        	<input type="hidden" name="trials" value='<?= json_encode($trials); ?>' />
            <input type="submit" class="btn" value="Ready? Let the game begin!" />
        </form>
        
        <div id="footer">
    	<p>Experimental design by Bas Cornelissen, Iliana Gioulatou, Jori Jansen and Arnold Kochari. For questions, please contact Bas via info at, well, bascornelissen.nl.</p>
    </div>
    </div>

    <div id="experiment-wrapper" class="cf">
    </div>
</div>
 
  	
</body>
</html>