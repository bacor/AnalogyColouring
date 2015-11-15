<?php if(isset($ACTIVE) === False) die('Bye bye!'); ?>
<!doctype html>
<html>
  
<head>
    <title><?= $page_title ?></title>
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>

<body>
<div id="wrapper">
    <div id="sidebar">
		<h1><strong>Thanks!</strong></h1>
        <p>That was it, thank you very much.</p>        
        <p class="message">
         	The more people do this experiment, the more reliable the results become. So please feel free to share this experiment with your friends!</p>

        <div id="footer">
    	<p>Experimental design by Bas Cornelissen, Iliana Gioulatou, Jori Jansen and Arnold Kochari. For questions, please contact Bas via info at, well, bascornelissen.nl.</p>
    </div>
    </div>
    

    <div id="experiment-wrapper" class="cf survey">
    <p><strong style="font-weight:bold;">How autistic are you?</strong>
    Based on the survey you just filled in, we can calculate a co called <em>autism quotient score</em>. Roughly, it measures how 'autistic' you are. If you want to see your AQ score, please click the button.
    </p>
    <p>
        <button onclick="document.getElementById('aq').style.display='block'">Show my AQ Score</button>
    </p>
    
    <section id="aq" style="display:none">
	    <div style="margin-top:50px;">
	    	<div style="background:#fff;">
		    <div style="width:<?=($score/50)*98?>%; min-width:2%; background-color: #16B772; padding: 10px; text-align:right; color:#fff">
			    <strong style="font-weight:bold"><?php echo $score ?></strong>
		    </div>
	    </div>
	    
	    <p style="margin-top:20px;"><strong style="font-weight:bold;">What does this mean?</strong> 
	    Citing <a href="http://aspergerstest.net/interpreting-aq-test-results/">this website</a>, 
	    <?php if($score <= 11): ?>
		    this is a very low score, indicating no tendency at all towards autistic traits.
	    <?php elseif(11< $score && $score <= 21): ?>
		    this is the average result that people get (many women average around 15 and men around 17)
		<?php elseif(21 < $score && $score <= 25): ?>
			this score shows autistic tendencies slightly above the population average.
		<?php elseif(25 < $score && $score <=31): ?>
			this score corresponds gives a borderline indication of an autism spectrum disorder. It is also possible to have aspergers or mild autism within this range.
		<?php else: ?>
			this score indicates a strong likelihood of Asperger syndrome or autism.
	    <?php endif; ?>
	    </p>
    </section>
    
    </div>
    </div>
</div>
 
  	
</body>
</html>